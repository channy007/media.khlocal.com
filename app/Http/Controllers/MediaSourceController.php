<?php

namespace App\Http\Controllers;

use App\Jobs\Uploader;
use App\Jobs\VideoCutter;
use App\Jobs\VideoDownloader;
use App\Models\FileStorage;
use App\Models\MediaProject;
use App\Models\MediaSource;
use App\Models\UserProject;
use App\Services\FileStorageService;
use App\Utils\Enums\MediaProjectStatus;
use App\Utils\Enums\MediaSourceStatus;
use App\Utils\Enums\QueueName;
use App\Utils\Enums\UserType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MediaSourceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $status = $request['status'];
        $search = $request['search'];
        $projectId = $request['project_id'] ?? 0;
        $project = MediaProject::whereId($projectId)->first();
        $datas = MediaSource::with(
            [
                'project' => function ($query) {
                    $query->select('id', 'name');
                },
                'channel_source',
                'creator'
            ]
        );

        $datas->when($status, function ($query) use ($status) {
            $query->whereStatus($status);
        })->when($user->type == UserType::EDITOR, function ($query) use ($user) {
            $query->whereCreatedById($user->id);
        })->when($search, function ($query) use ($search) {
            $query->where('source_name', 'LIKE', '%' . $search . '%')
                ->orWhere('source_url', 'LIKE', '%' . $search . '%')
                ->orWhere('source_text', 'LIKE', '%' . $search . '%');
        })->when($projectId, function ($query) use ($projectId) {
            $query->whereProjectId($projectId);
        });
        $datas = $datas->orderBy('id', 'desc')->paginate(10);

        return view('media_source.index', compact('datas', 'status', 'search', 'project'));
    }

    public function create(Request $request)
    {
        $projects = $this->getMediaProject();
        return view('media_source.create', compact('projects'));
    }

    private function getMediaProject()
    {
        $user = auth()->user();

        if ($user->type == UserType::ADMIN) {
            return MediaProject::with('channel_sources.channel_source')->whereStatus(MediaProjectStatus::ACTIVE)->get();
        }
        $userProjects = UserProject::with('media_project')->whereUserId($user->id)->get();

        return $userProjects->pluck('media_project');
    }


    public function store(Request $request)
    {
        Log::info("===== reqeust in");
        $request['status'] = MediaSourceStatus::NEW;
        $user = auth()->user();
        $request['created_by_id'] = optional($user)->id;
        $filePath = $request['file_path'];
        $sourceUrl = $request['source_url'];

        $validator = Validator::make(
            $request->all(),
            [
                'source_url' => [
                    Rule::unique('media_sources')->where(function ($query) use ($sourceUrl) {
                        return $query->whereNotNull('source_url')->whereSourceUrl($sourceUrl);
                    })
                ],
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        if ($request->hasFile('thumbnail')) {
            $thumb = $request['thumbnail'];
            $request['thumb'] = Storage::disk('public')->put('images', $thumb);
        }

        $request['status'] = MediaSourceStatus::NEW;

        try{
            $mediaSource = MediaSource::create($request->all());
            if(!$mediaSource){
                return redirect()->back()->withErrors("Something went wrong! can not create media source.");
            }

            $mediaSource->refresh();
            $sourceFile = $request->file('source_file');
            if ($sourceFile) {
                $fileStorage = FileStorageService::createFileStorage($mediaSource);
                $path = Storage::disk('public')->put('videos', $sourceFile);
                $fileName = $fileStorage->name . '.' . $fileStorage->extension;
                $sourceFile->storeAs('videos', $fileName, 'public');
                dispatch(new VideoCutter(
                    [
                        'mediaSource' => $mediaSource,
                        'fileStorage' => $fileStorage
                    ]
                ))->onQueue(QueueName::VIDEO_CUTTER)->delay(5);
            }else if($filePath){
                $fileStorage = FileStorageService::createFileStorageByFilePath($mediaSource,$filePath);

                dispatch(new VideoCutter(
                    [
                        'mediaSource' => $mediaSource,
                        'fileStorage' => $fileStorage
                    ]
                ))->onQueue(QueueName::VIDEO_CUTTER)->delay(5);
            }else {
                dispatch(new VideoDownloader(
                    [
                        'mediaSource' => $mediaSource
                    ]
                ))->onQueue(QueueName::VIDEO_DOWNLOADER);
            }
            return response()->json(['success' => "Create successfully."]);

        }catch(Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $data = MediaSource::whereId($id)->first();

        return view('media_source.edit', compact('data'));
    }

    public function retryDownload(Request $request, $mediaSourceId)
    { 
        Log::info("===== retry download =====");

        $mediaSource = MediaSource::whereId($mediaSourceId)->where('status', '<>', MediaSourceStatus::DOWNLOADING)->first();

        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }
        $mediaSource->status = MediaSourceStatus::PENDING_DOWNLOAD;
        $mediaSource->save();
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

        $mediaSource = MediaSource::whereId($mediaSourceId)->where('status', '<>', MediaSourceStatus::CUTTING)->first();
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }
        $mediaSource->status = MediaSourceStatus::PENDING_CUT;
        $mediaSource->update($request->all());

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

        $mediaSource = MediaSource::whereId($mediaSourceId)->where('status', '<>', MediaSourceStatus::UPLOADING)->first();
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$mediaSource) {
            return redirect()->back()->withErrors("Media source record not found!");
        }
        $mediaSource->status = MediaSourceStatus::PENDING_UPLOAD;
        $mediaSource->update($request->all());
        dispatch(new Uploader([
            'mediaSource' => $mediaSource,
            'fileStorage' => $fileStorage
        ]))->onQueue(QueueName::UPLOADER)->delay(2);

        return redirect()->route('media-source-index')
            ->with('success', 'Record start uploading.');
    }

    public function viewVideoCutted(Request $request, $mediaSourceId)
    {
        $data = FileStorage::whereMediaSourceId($mediaSourceId)->first();
        return view('media_source.video.display_video_cutted', compact('data'));
    }

    public function viewVideoDownloaded(Request $request, $mediaSourceId)
    {
        $data = FileStorage::whereMediaSourceId($mediaSourceId)->first();
        return view('media_source.video.display_video_downloaded', compact('data'));
    }

    public function viewVideo(Request $request, $mediaSourceId)
    {
        $data = FileStorage::whereMediaSourceId($mediaSourceId)->first();
        return view('media_source.video.display_video', compact('data'));
    }
}
