<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $datas = User::with('projects.media_project')->paginate(10);

        return view('user.index', compact('datas'));
    }

    public function edit(Request $request, $id)
    {
        $data = User::with('projects.media_project')->whereId($id)->first();

        return view('user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $user = User::whereId($id)->first();

        $validator = Validator::make($request->all(), ['username' => 'unique:users,username,' . $id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if (isset($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }
        if ($user) {
            $user->update($request->all());
            $this->updateOrCreateUserProjects($user, $request);
        }
        return redirect()->route('user-index')
            ->with('success', 'Update successfully.');
    }

    public function create(Request $request)
    {
        return view('user.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['username' => 'unique:users,username']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        if (isset($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }
        $user = User::create($request->all());
        $this->updateOrCreateUserProjects($user, $request);

        return redirect()->route('user-index')
            ->with('success', 'Create successfully.');
    }

    private function updateOrCreateUserProjects($user, $request)
    {
        $projectIds =  $request['project_ids'];
        if (!isset($projectIds) || empty($projectIds)) {
            $projectIds = [];
        }

        UserProject::whereUserId($user->id)
            ->whereNotIn('media_project_id', $projectIds)->delete();

        foreach ($projectIds as $projectId) {
            UserProject::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'media_project_id' => $projectId
                ],
                [
                    'user_id' => $user->id,
                    'media_project_id' => $projectId
                ]
            );
        }
    }
}
