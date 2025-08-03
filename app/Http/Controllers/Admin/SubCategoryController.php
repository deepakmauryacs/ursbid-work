<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the sub categories.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $subs = DB::table('sub')
            ->leftJoin('category', 'sub.cat_id', '=', 'category.id')
            ->select('sub.*', 'category.title as category_title')
            ->orderBy('sub.order_by')
            ->paginate($perPage);

        return view('ursbid-admin.sub_categories.list', compact('subs', 'perPage'));
    }

    /**
     * Show the form for creating a new sub category.
     */
    public function create()
    {
        $categories = DB::table('category')->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.sub_categories.create', compact('categories'));
    }

    /**
     * Store a newly created sub category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer',
            'post_date' => 'required|date_format:d-m-Y',
            'order_by' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $exists = DB::table('sub')
            ->where('cat_id', $validated['cat_id'])
            ->where('title', $validated['title'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sub category already exists for this category.',
                'errors' => ['title' => ['Sub category already exists for this category.']],
            ], 422);
        }

        $data = [
            'title' => $validated['title'],
            'cat_id' => $validated['cat_id'],
            'post_date' => $validated['post_date'],
            'order_by' => $validated['order_by'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
        ];

        $id = DB::table('sub')->insertGetId($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            DB::table('sub')->where('id', $id)->update(['image' => $filename]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category created successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified sub category.
     */
    public function edit($id)
    {
        $sub = DB::table('sub')->where('id', $id)->first();
        if (!$sub) {
            abort(404);
        }
        $categories = DB::table('category')->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.sub_categories.edit', compact('sub', 'categories'));
    }

    /**
     * Update the specified sub category in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer',
            'post_date' => 'required|date_format:d-m-Y',
            'order_by' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $exists = DB::table('sub')
            ->where('cat_id', $validated['cat_id'])
            ->where('title', $validated['title'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sub category already exists for this category.',
                'errors' => ['title' => ['Sub category already exists for this category.']],
            ], 422);
        }

        $sub = DB::table('sub')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
        }

        $data = [
            'title' => $validated['title'],
            'cat_id' => $validated['cat_id'],
            'post_date' => $validated['post_date'],
            'order_by' => $validated['order_by'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
        ];

        if ($request->hasFile('image')) {
            if (!empty($sub->image)) {
                $path = public_path('uploads/' . $sub->image);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        }

        DB::table('sub')->where('id', $id)->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category updated successfully.',
        ]);
    }

    /**
     * Remove the specified sub category from storage.
     */
    public function destroy($id)
    {
        $sub = DB::table('sub')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
        }

        if (!empty($sub->image)) {
            $path = public_path('uploads/' . $sub->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        DB::table('sub')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category deleted successfully.',
        ]);
    }
}
