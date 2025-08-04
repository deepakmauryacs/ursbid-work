<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnPageSeo;
use Illuminate\Http\Request;

class OnPageSeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $seos = OnPageSeo::orderByDesc('id')->paginate($perPage);
        return view('ursbid-admin.on_page_seo.list', compact('seos', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ursbid-admin.on_page_seo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_url' => 'required|string|max:255|unique:on_page_seo,page_url',
            'page_name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        OnPageSeo::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'On Page SEO created successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $seo = OnPageSeo::findOrFail($id);
        return view('ursbid-admin.on_page_seo.edit', compact('seo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $seo = OnPageSeo::findOrFail($id);

        $validated = $request->validate([
            'page_url' => 'required|string|max:255|unique:on_page_seo,page_url,' . $seo->id,
            'page_name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $seo->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'On Page SEO updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $seo = OnPageSeo::findOrFail($id);
        $seo->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'On Page SEO deleted successfully.',
        ]);
    }
}

