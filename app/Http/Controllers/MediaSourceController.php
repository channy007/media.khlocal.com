<?php

namespace App\Http\Controllers;

use App\Jobs\VideoDownloader;
use App\Models\MediaProject;
use App\Models\MediaSource;
use App\Utils\enums\QueueName;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        )->paginate(10);

        return view('media_source.index', compact('datas'));
    }

    public function create(Request $request)
    {
        $projects = MediaProject::all();
        return view('media_source.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request['created_at'] = Carbon::now();
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
}
