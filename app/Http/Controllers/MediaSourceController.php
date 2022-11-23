<?php

namespace App\Http\Controllers;

use App\Jobs\Uploader;
use App\Jobs\VideoCutter;
use App\Jobs\VideoDownloader;
use App\Models\MediaProject;
use App\Models\MediaSource;
use App\Utils\enums\MediaProjectStatus;
use App\Utils\enums\MediaSourceStatus;
use App\Utils\enums\QueueName;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MediaSourceController extends Controller
{
    public function index(Request $request)
    {
        $datas = MediaSource::with(
            [
                'project' => function ($query) {
                    $query->select('id', 'name');
                }
            ]
        )->orderBy('id', 'desc')->paginate(10);

        return view('media_source.index', compact('datas'));
    }

    public function create(Request $request)
    {
        $projects = MediaProject::with('channel_sources.channel_source')->whereStatus(MediaProjectStatus::ACTIVE)->get();
        return view('media_source.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request['status'] = MediaSourceStatus::NEW;

        $validator = Validator::make($request->all(), ['source_url' => 'unique:media_sources,source_url']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        if ($request->hasFile('thumbnail')) {
            $thumb = $request['thumbnail'];
            $request['thumb'] = Storage::disk('public')->put('images', $thumb);
        }
        $mediaSource = MediaSource::create($request->all());

        if ($mediaSource) {
            $mediaSource->refresh();
            dispatch(new VideoDownloader(
                [
                    'mediaSource' => $mediaSource
                ]
            ))->onQueue(QueueName::VIDEO_DOWNLOADER);
        }

        return redirect()->route('media-source-index')
            ->with('success', 'Create successfully.');
    }


    public function edit(Request $request, $id)
    {
        $data = MediaSource::whereId($id)->first();

        return view('media_source.edit', compact('data'));
    }

    public function retryDownload(Request $request, $mediaSourceId)
    {
        $mediaSource = MediaSource::whereId($mediaSourceId)->first();

        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }

        dispatch(new VideoDownloader(
            [
                'mediaSource' => $mediaSource
            ]
        ))->onQueue(QueueName::VIDEO_DOWNLOADER);

        return redirect()->route('media-source-index')
            ->with('success', 'Record start downloading.');
    }


    public function returyCut(Request $request, $mediaSourceId)
    {
        $mediaSource = MediaSource::whereId($mediaSourceId)->first();

        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }

        dispatch(new VideoCutter(
            [
                'mediaSource' => $mediaSource,
                'fileProperty' => $this->prepareFileProperties($mediaSource)
            ]
        ))->onQueue(QueueName::VIDEO_CUTTER)->delay(5);

        return redirect()->route('media-source-index')
            ->with('success', 'Record start cutting.');
    }

    private function prepareFileProperties($mediaSource)
    {
        $fileProperty = [
            'path' => public_path('storage') . '/videos',
            'originalName' => Str::slug($mediaSource->source_name),
            'extension' => '.mp4',
            'cuttedFileName' => Str::slug($mediaSource->source_name) . '_cut'
        ];

        return $fileProperty;
    }

    public function retryUpload(Request $request, $mediaSourceId)
    {
        $mediaSource = MediaSource::whereId($mediaSourceId)->first();

        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }

        dispatch(new Uploader([
            'mediaSource' => $mediaSource,
            'fileProperty' => $this->prepareFileProperties($mediaSource)
        ]))->onQueue(QueueName::UPLOADER)->delay(2);

        return redirect()->route('media-source-index')
        ->with('success', 'Record start uploading.');
    }
}
