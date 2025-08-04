<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\YoutubeLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class YoutubeLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = YoutubeLink::orderByDesc('created_at')->paginate(10);
        return view('ursbid-admin.youtube_links.list', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ursbid-admin.youtube_links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'youtube_link' => 'required|url',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        YoutubeLink::create($request->only('youtube_link', 'status'));

        return response()->json(['message' => 'Youtube link added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $link = YoutubeLink::findOrFail($id);
        return view('ursbid-admin.youtube_links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'youtube_link' => 'required|url',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $link = YoutubeLink::findOrFail($id);
        $link->update($request->only('youtube_link', 'status'));

        return response()->json(['message' => 'Youtube link updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = YoutubeLink::findOrFail($id);
        $link->delete();

        return response()->json(['message' => 'Youtube link deleted successfully']);
    }
}
