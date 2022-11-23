<?php

namespace App\Http\Controllers;

use App\Jobs\Uploader;
use App\Jobs\VideoCutter;
use App\Jobs\VideoDownloader;
use App\Models\FileStorage;
use App\Models\MediaProject;
use App\Models\MediaSource;
use App\Utils\enums\MediaProjectStatus;
use App\Utils\enums\MediaSourceStatus;
use App\Utils\enums\QueueName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaSourceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request['status'];

        $datas = MediaSource::with(
            [
                'project' => function ($query) {
                    $query->select('id', 'name');
                },
                'channel_source'
            ]
            );
        $datas->when($status,function($query) use($status){
            $query->whereStatus($status);
        });    
        $datas = $datas->orderBy('id', 'desc')->paginate(10);

        return view('media_source.index', compact('datas','status'));
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
        Log::info("===== retry download =====");

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


    public function retryCut(Request $request, $mediaSourceId)
    {
        Log::info("===== retry cut =====");
        $mediaSource = MediaSource::whereId($mediaSourceId)->first();
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }

        dispatch(new VideoCutter(
            [
                'mediaSource' => $mediaSource,
                'fileStorage' => $fileStorage
            ]
        ))->onQueue(QueueName::VIDEO_CUTTER)->delay(5);

        return redirect()->route('media-source-index')
            ->with('success', 'Record start cutting.');
    }

    public function retryUpload(Request $request, $mediaSourceId)
    {
        Log::info("===== retry upload =====");

        $mediaSource = MediaSource::whereId($mediaSourceId)->first();
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }

        dispatch(new Uploader([
            'mediaSource' => $mediaSource,
            'fileStorage' => $fileStorage
        ]))->onQueue(QueueName::UPLOADER)->delay(2);

        return redirect()->route('media-source-index')
            ->with('success', 'Record start uploading.');
    }
}
