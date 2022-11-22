<?php

namespace App\Http\Controllers;

use App\Models\ChannelSource;
use App\Models\ProjectChannelSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $datas = ChannelSource::paginate(10);

        return view('channel_source.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = ChannelSource::whereId($id)->first();


        return view('channel_source.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $channelSource = ChannelSource::whereId($id)->first();
        $user = auth()->user();
        if ($channelSource) {
            $channelSource->updated_by_id = optional($user)->id;
            $channelSource->update($request->all());
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
        $channelSource = ChannelSource::create($request->all());
        return redirect()->route('channel-source-index')
            ->with('success', 'Create successfully.');
    }
}
