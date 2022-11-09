<?php

namespace App\Http\Controllers;

use App\Models\MediaProject;
use App\Models\MediaSource;
use Illuminate\Http\Request;

class MediaSourceController extends Controller
{
    public function index(Request $request)
    {
        $datas = MediaSource::paginate(10);

        return view('media_source.index', compact('datas'));
    }

    public function create(Request $request)
    {
        $projects = MediaProject::all();
        return view('media_source.create',compact('projects'));
    }

    public function store(Request $request)
    {
        return view('media_source.create');
    }


    public function edit(Request $request, $id)
    {
        $data = MediaSource::whereId($id)->first();


        return view('media_source.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $mediaProject = MediaSource::whereId($id)->first();
        if ($mediaProject) {
            $mediaProject->update($request->all());
        }
        return redirect()->route('media-project-index')
            ->with('success', 'Update successfully.');
    }
}
