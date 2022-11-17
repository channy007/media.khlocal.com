<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $datas = Application::paginate(10);

        return view('application.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = Application::whereId($id)->first();


        return view('application.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::whereId($id)->first();
        if ($application) {
            $application->update($request->all());
        }
        return redirect()->route('app-index')
            ->with('success', 'Update successfully.');
    }

    public function create(Request $request)
    {
        return view('application.create');
    }

    public function store(Request $request)
    {
        $mediaSource = Application::create($request->all());

        return redirect()->route('app-index')
            ->with('success', 'Create successfully.');
    }
}
