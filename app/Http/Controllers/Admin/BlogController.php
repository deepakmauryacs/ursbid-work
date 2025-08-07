<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $blogs = DB::table('blogs')
            ->orderByDesc('id')
            ->paginate($perPage);

        return view('ursbid-admin.blogs.list', compact('blogs', 'perPage'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('ursbid-admin.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title',
            'description' => 'required|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'custom_header_code' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'post_date' => now()->format('d-m-Y'),
            'description' => $validated['description'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'custom_header_code' => $request->custom_header_code,
        ];

        $id = DB::table('blogs')->insertGetId($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('uploads/blog');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            DB::table('blogs')->where('id', $id)->update([
                'image' => 'uploads/blog/' . $filename,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully.',
        ]);
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        if (!$blog) {
            abort(404);
        }
        return view('ursbid-admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title,' . $id,
            'description' => 'required|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'custom_header_code' => 'nullable|string',
        ]);

        $blog = DB::table('blogs')->where('id', $id)->first();
        if (!$blog) {
            return response()->json(['message' => 'Blog not found.'], 404);
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'custom_header_code' => $request->custom_header_code,
        ];

        if ($request->hasFile('image')) {
            if (!empty($blog->image) && File::exists(public_path($blog->image))) {
                File::delete(public_path($blog->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('uploads/blog');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $data['image'] = 'uploads/blog/' . $filename;
        }

        DB::table('blogs')->where('id', $id)->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog updated successfully.',
        ]);
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        if (!$blog) {
            return response()->json(['message' => 'Blog not found.'], 404);
        }

        if (!empty($blog->image)) {
            $path = public_path($blog->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        DB::table('blogs')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog deleted successfully.',
        ]);
    }
}
