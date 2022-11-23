<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ChannelSource;
use App\Models\MediaProject;
use App\Models\ProjectChannelSource;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaProjectController extends Controller
{
    public function index(Request $request)
    {
        $datas = MediaProject::with('application', 'channel_sources.channel_source')->paginate(10);

        return view('media_project.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = MediaProject::whereId($id)->first();
        $applications = Application::all();
        $channelSources = ChannelSource::all();

        return view('media_project.edit', compact('data', 'applications', 'channelSources'));
    }

    public function update(Request $request, $id)
    {
        $mediaProject = MediaProject::whereId($id)->first();
        if (!$mediaProject) {
            return redirect()->back()->withErrors("Selected Application not found!");
        }
        $result = DB::transaction(function () use ($mediaProject, $request) {
            $oldToken = $mediaProject->access_token;
            $mediaProject->update($request->all());
            $result = ['success' => true, 'message' => 'Successful', 'errors' => null];
            if ($oldToken != $request['access_token']) {
                $result = $this->generateLongLifeToken($mediaProject);
            }
            $this->updateOrCreateProjectChannelSources($mediaProject, $request);

            if (!$result['success']) {
                DB::rollBack();
            }
            return $result;
        });
        if (!$result['success']) {
            return redirect()->back()->withErrors($result['errors']);
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
        $result = DB::transaction(function () use ($request) {
            $mediaProject = MediaProject::create($request->all());
            $result = ['success' => true, 'message' => 'Successful', 'errors' => null];
            if ($mediaProject) {
                $result = $this->generateLongLifeToken($mediaProject);
            }
            $this->updateOrCreateProjectChannelSources($mediaProject, $request);
            if (!$result['success']) {
                DB::rollBack();
            }
            return $result;
        });
        if (!$result['success']) {
            return redirect()->back()->withErrors($result['errors']);
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
                $result = json_decode($facebookResponse->body());
                $mediaProject->long_access_token = $result->access_token;
                $mediaProject->expire_at = Carbon::now()->addDays((int)($result->expires_in / 86400));
                $mediaProject->created_token_at = Carbon::now();
                $mediaProject->save();
                Log::info("============ generat long life token success response: " . $facebookResponse->body());
            }
            if ($facebookResponse->failed()) {
                Log::info("============ generat long life token fails response: " . $facebookResponse->body());
                $result['success'] = false;
                $result['errors'] = "Facebook generate long token " . json_decode($facebookResponse->body())->error->message;
            }

            return $result;
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
