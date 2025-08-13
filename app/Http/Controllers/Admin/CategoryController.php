<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
       $categories = Category::orderByDesc('id')->paginate(10);
        return view('ursbid-admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('ursbid-admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $data = $validator->validated();
        $data['slug'] = Str::slug($data['name']);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/category'), $filename);
            $data['image'] = 'uploads/category/' . $filename;
        }
    
        // Create new category
        $category = Category::create($data);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully.',
            'data' => $category,
        ]);
}


    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('ursbid-admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $data = $validator->validated();
        $data['slug'] = Str::slug($data['name']);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path($category->image))) {
                @unlink(public_path($category->image));
            }
    
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/category'), $filename);
            $data['image'] = 'uploads/category/' . $filename;
        }
    
        // Update category
        $category->update($data);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully.',
            'data' => $category,
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully.',
        ]);
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image && file_exists(public_path($category->image))) {
            @unlink(public_path($category->image));
        }
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully.',
        ]);
    }
}
