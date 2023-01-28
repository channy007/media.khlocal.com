<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{

    private function test(){
        // Initialize URL to the variable
        $url = 'https://www.youtube.com/watch?v=rvje5oblrLw&list=RDrvje5oblrLw&start_radio=1';
            
        // Use parse_url() function to parse the URL
        // and return an associative array which
        // contains its various components
        $url_components = parse_url($url);
        
        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components['query'], $params);
        Log::info("============================== query: ".$params['v']);

    }


    public function index(Request $request)
    {
        $this->test();
        $search = $request['search'];
        $datas = Application::query();
        $datas->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
        $datas = $datas->paginate(10);



        return view('application.index', compact('datas','search'));
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
