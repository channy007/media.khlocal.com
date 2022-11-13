<?php

namespace App\Http\Controllers;

use App\Models\MediaProject;
use Illuminate\Http\Request;

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


        return view('media_project.edit', compact('data'));
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
        return view('media_project.create');
    }

    public function store(Request $request)
    {
        $mediaSource = MediaProject::create($request->all());

        return redirect()->route('media-project-index')
            ->with('success', 'Create successfully.');
    }
}
