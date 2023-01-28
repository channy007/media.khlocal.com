<?php

namespace App\Http\Controllers;

use App\Models\MediaTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaTagController extends Controller
{
    public function listMediaTags(Request $request)
    {
        $search = $request['search'];
        $mediaTags = MediaTag::query();
        $mediaTags->when($search, function ($query) use ($search) {
            $query->where('tag_name', 'LIKE', '%' . $search . '%');
        });
        return response(['data' => $mediaTags->limit(10)->get()]);
    }

    public function index(Request $request)
    {
        $search = $request['search'];
        $datas = MediaTag::query();
        $datas->when($search,function($query)use($search){
            $query->where('tag_name','LIKE','%'.$search.'%')
            ->orWhere('tag_description','LIKE','%'.$search.'%');
        });
        $datas = $datas->paginate(10);

        return view('media_tag.index', compact('datas','search'));
    }

    public function edit(Request $request, $id)
    {
        $data = MediaTag::whereId($id)->first();


        return view('media_tag.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), ['url' => 'unique:channel_sources,url,'.$id]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $mediaTag = MediaTag::whereId($id)->first();
        $user = auth()->user();
        if ($mediaTag) {
            $mediaTag->update($request->all());

        }
        return redirect()->route('media-tag-index')
            ->with('success', 'Update successfully.');
    }

    public function create(Request $request)
    {
        return view('media_tag.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request['created_by_id'] = optional($user)->id;

        $validator = Validator::make($request->all(), ['url' => 'unique:channel_sources,url']);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }
        
        $mediaTag = MediaTag::create($request->all());
        

        return redirect()->route('media-tag-index')
            ->with('success', 'Create successfully.');
    }
}
