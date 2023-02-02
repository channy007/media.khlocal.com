<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ChannelSource;
use App\Models\MediaProject;
use App\Models\MediaTag;
use App\Models\ProjectChannelSource;
use App\Models\UserProject;
use App\Services\MediaProjectService;
use App\Utils\Generics\ResponseDTO;
use App\Utils\Enums\UserType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MediaProjectController extends Controller
{
    private $mediaProjectService;

    public function __construct(MediaProjectService $mediaProjectService)
    {
        $this->mediaProjectService = $mediaProjectService;
    }


    public function listMediaProjects(Request $request)
    {
        $search = $request['search'];
        $mediaProject = MediaProject::query();
        $mediaProject->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
        return response(['data' => $mediaProject->limit(10)->get()]);
    }


    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request['search'];

        $mediaProjects = MediaProject::with('application', 'channel_sources.channel_source');
        if ($user->type == UserType::EDITOR) {
            $mediaProjectIds = UserProject::whereUserId($user->id)->get()->pluck('media_project_id');
            $mediaProjects->whereIn('id',$mediaProjectIds);
        }
        $mediaProjects->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });

        $datas = $mediaProjects->paginate(10);

        return view('media_project.index', compact('datas','search'));
    }



    public function edit(Request $request, $id)
    {
        $data = MediaProject::whereId($id)->first();
        $applications = Application::all();
        $channelSources = ChannelSource::all();
        $data->media_tags = $data->tags ? MediaTag::whereIn('tag_id',explode(",",$data->tags))->get() : null;

        return view('media_project.edit', compact('data', 'applications', 'channelSources'));
    }

    public function update(Request $request, $id)
    {
        $mediaProject = MediaProject::whereId($id)->first();
        if (!$mediaProject) {
            return redirect()->back()->withErrors("Selected Application not found!");
        }
        $validator = Validator::make($request->all(), ['page_id' => 'unique:media_projects,page_id,'.$id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $result = DB::transaction(function () use ($mediaProject, $request) {
            $oldToken = $mediaProject->short_user_access_token;
            $request['tags'] = $request['tags'] ? implode(",",$request['tags']) : null;
            $mediaProject->update($request->all());
            $result = new ResponseDTO([]);// ['success' => true, 'message' => 'Successful', 'errors' => null];
            if ($oldToken != $request['short_user_access_token']) {
                $result = $this->mediaProjectService->updateToken($mediaProject);
            }
            $this->updateOrCreateProjectChannelSources($mediaProject, $request);

            if ($result->hasError()) {
                DB::rollBack();
            }
            return $result;
        });
        if ($result->hasError()) {
            return redirect()->back()->withErrors($result->error);
        }

        return redirect()->route('media-project-index')
            ->with('success', 'Update successfully.');
    }

    public function create(Request $request)
    {
        $applications = Application::all();
        return view('media_project.create', compact('applications'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['page_id' => 'unique:media_projects,page_id']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        
        $result = DB::transaction(function () use ($request) {
            $request['tags'] = $request['tags'] ? implode(",",$request['tags']) : null;
            $mediaProject = MediaProject::create($request->all());
            $result = new ResponseDTO([]);
            if ($mediaProject) {
                $result = $this->mediaProjectService->updateToken($mediaProject);
            }
            $this->updateOrCreateProjectChannelSources($mediaProject, $request);

            if ($result->hasError()) {
                DB::rollBack();
            }
            return $result;
        });

        if ($result->hasError()) {
            return redirect()->back()->withErrors($result->error);
        }
        return redirect()->route('media-project-index')
            ->with('success', 'Update successfully.');
    }

    private function generateLongLifeToken($mediaProject)
    {
        $result = ['success' => true, 'message' => 'Successful', 'errors' => null];

        if (!isset($mediaProject->access_token)) {
            return $result;
        }
        $application = Application::whereId($mediaProject->application_id)->first();

        try {
            Log::info("============ starting generat long life token ============");
            $facebookUrl = 'https://graph.facebook.com/v15.0/oauth/access_token';
            $params = [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $application->app_id,
                'client_secret' => $application->client_secret,
                'fb_exchange_token' => $mediaProject->access_token
            ];
            $timeOut = 20;
            $facebookResponse = Http::asJson()->timeout($timeOut)->post(
                $facebookUrl . "?" . http_build_query($params)
            );

            if ($facebookResponse->successful()) {
                $facebookResult = json_decode($facebookResponse->body());
                $mediaProject->long_access_token = $facebookResult->access_token;
                $mediaProject->expire_at = Carbon::now()->addDays((int)($facebookResult->expires_in / 86400));
                $mediaProject->created_token_at = Carbon::now();
                $mediaProject->save();
                Log::info("============ generate long life token success response: " . $facebookResponse->body());
            }
            if ($facebookResponse->failed()) {
                Log::info("============ generat long life token fails response: " . $facebookResponse->body());
                $result['success'] = false;
                $result['errors'] = "Facebook generate long token " . json_decode($facebookResponse->body())->error->message;
            }
        } catch (Exception $e) {
            Log::info("============ generat long life token error ============" . $e->getMessage());
            $result['success'] = false;
            $result['errors'] = $e->getMessage();
        } finally {
            return $result;
        }
    }

    private function updateOrCreateProjectChannelSources($mediaProject, $request)
    {
        $channelSourceIds =  $request['channel_source_ids'];
        if (!isset($channelSourceIds) || empty($channelSourceIds)) {
            $channelSourceIds = [];
        }

        ProjectChannelSource::whereProjectId($mediaProject->id)
            ->whereNotIn('channel_source_id', $channelSourceIds)->delete();

        foreach ($channelSourceIds as $channelSourceId) {
            ProjectChannelSource::updateOrCreate(
                [
                    'project_id' => $mediaProject->id,
                    'channel_source_id' => $channelSourceId
                ],
                [
                    'project_id' => $mediaProject->id,
                    'channel_source_id' => $channelSourceId
                ]
            );
        }
    }
}
