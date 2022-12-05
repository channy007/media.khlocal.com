<?php

namespace App\Http\Controllers;

use App\Models\ChannelSource;
use App\Models\ProjectChannelSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChannelSourceController extends Controller
{

    public function listChannelSources(Request $request)
    {
        $search = $request['search'];
        $channelSources = ChannelSource::query();
        $channelSources->when($search, function ($query) use ($search) {
            $query->where('channel', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%');
        });
        return response(['data' => $channelSources->limit(10)->get()]);
    }

    public function index(Request $request)
    {
        $datas = ChannelSource::with('media_projects.project')->paginate(10);

        return view('channel_source.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = ChannelSource::whereId($id)->first();


        return view('channel_source.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['url' => 'unique:channel_sources,url,'.$id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $channelSource = ChannelSource::whereId($id)->first();
        $user = auth()->user();
        if ($channelSource) {
            $channelSource->updated_by_id = optional($user)->id;
            $channelSource->update($request->all());
            $this->updateOrCreateProjectChannelSources($channelSource,$request);

        }
        return redirect()->route('channel-source-index')
            ->with('success', 'Update successfully.');
    }

    public function create(Request $request)
    {
        return view('channel_source.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request['created_by_id'] = optional($user)->id;

        $validator = Validator::make($request->all(), ['url' => 'unique:channel_sources,url']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        
        $channelSource = ChannelSource::create($request->all());
        if($channelSource){
            $this->updateOrCreateProjectChannelSources($channelSource,$request);
        }

        return redirect()->route('channel-source-index')
            ->with('success', 'Create successfully.');
    }

    private function updateOrCreateProjectChannelSources($channelSource, $request)
    {
        $mediaProjectIds =  $request['media_project_ids'];
        if (!isset($mediaProjectIds) || empty($mediaProjectIds)) {
            $mediaProjectIds = [];
        }

        ProjectChannelSource::whereChannelSourceId($channelSource->id)
            ->whereNotIn('project_id', $mediaProjectIds)->delete();

        foreach ($mediaProjectIds as $projectId) {
            ProjectChannelSource::updateOrCreate(
                [
                    'project_id' => $projectId,
                    'channel_source_id' => $channelSource->id
                ],
                [
                    'project_id' => $projectId,
                    'channel_source_id' => $channelSource->id
                ]
            );
        }
    }
}
