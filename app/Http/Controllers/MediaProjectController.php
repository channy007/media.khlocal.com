<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MediaProject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MediaProjectController extends Controller
{
    public function index(Request $request)
    {
        $datas = MediaProject::paginate(10);

        return view('media_project.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = MediaProject::whereId($id)->first();
        $applications = Application::all();

        return view('media_project.edit', compact('data', 'applications'));
    }

    public function update(Request $request, $id)
    {
        $mediaProject = MediaProject::whereId($id)->first();
        if ($mediaProject) {
            $mediaProject->update($request->all());
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
        $mediaProject = MediaProject::create($request->all());
        if ($mediaProject) {
            $this->generateLongLifeToken($mediaProject);
        }
        return redirect()->route('media-project-index')
            ->with('success', 'Create successfully.');
    }

    private function generateLongLifeToken($mediaProject)
    {
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

                $mediaProject->save();
                Log::info("============ generat long life token success response: " . $facebookResponse->body());
            }
            if ($facebookResponse->failed()) {
                Log::info("============ generat long life token fails response: " . $facebookResponse->body());
            }
        } catch (Exception $e) {
            Log::info("============ generat long life token error ============" . $e->getMessage());
        }
    }
}
