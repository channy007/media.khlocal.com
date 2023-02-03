<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Services\ApplicationService;
use App\Utils\Generics\ResponseDTO;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    private $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }


    public function index(Request $request)
    {
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
        $result = new ResponseDTO([]);
        if ($application) {
            $oldToken = $application->short_access_token;
            $application->update($request->all());

            if ($oldToken != $request['short_access_token']) {
                $result = $this->applicationService->updateToken($application);
            }
        }

        if($result->hasError()){
            return redirect()->back()->withErrors($result->error);
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
