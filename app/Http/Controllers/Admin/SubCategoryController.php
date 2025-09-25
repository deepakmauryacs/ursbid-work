<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the sub categories.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|integer|exists:categories,id',
            'name'     => 'nullable|string|max:255',
            'per_page' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $perPage = $request->input('per_page', 10);

        $query = DB::table('sub_categories')
            ->leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('sub_categories.*', 'categories.name as category_name')
            ->orderBy('sub_categories.order_by');

        if ($request->filled('category')) {
            $query->where('sub_categories.category_id', $request->category);
        }

        if ($request->filled('name')) {
            $query->where('sub_categories.name', 'like', '%' . $request->name . '%');
        }

        $subs = $query->paginate($perPage)->appends($request->all());

        $categories = DB::table('categories')->where('status', '1')->orderBy('name')->get();

        if ($request->ajax()) {
            return view('ursbid-admin.sub_categories.partials.table', compact('subs'))->render();
        }

        return view('ursbid-admin.sub_categories.list', compact('subs', 'perPage', 'categories'));
    }

    /**
     * Show the form for creating a new sub category.
     */
    public function create()
    {
        $categories = DB::table('categories')->where('status', '1')->orderBy('name')->get();
        return view('ursbid-admin.sub_categories.create', compact('categories'));
    }

    /**
     * Store a newly created sub category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'order_by' => 'nullable|integer',
            'status' => 'required|in:1,2',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $exists = DB::table('sub_categories')
            ->where('category_id', $validated['category_id'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sub category already exists for this category.',
                'errors' => ['name' => ['Sub category already exists for this category.']],
            ], 422);
        }

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'order_by' => $validated['order_by'],
            'status' => $validated['status'],
            'description' => $validated['description'],
            'tags' => isset($validated['tags']) ? json_encode(array_map('trim', explode(',', $validated['tags']))) : json_encode([]),
            'slug' => Str::slug($validated['name']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
        ];

        $id = DB::table('sub_categories')->insertGetId($data);

       // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
    
            // Ensure directory exists
            $uploadPath = public_path('uploads/sub_category');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            // Move uploaded file
            $file->move($uploadPath, $filename);
    
            // Update database with image path
            DB::table('sub_categories')->where('id', $id)->update([
                'image' => 'uploads/sub_category/' . $filename
            ]);
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
        $sub = DB::table('sub_categories')->where('id', $id)->first();
        if (!$sub) {
            abort(404);
        }
        
        $categories = DB::table('categories')->where('status', '1')->orderBy('name')->get();
        
        // Get the category slug for the selected category
        $categorySlug = '';
        if ($sub->category_id) {
            $category = DB::table('categories')->where('id', $sub->category_id)->first();
            $categorySlug = $category->slug ?? '';
        }
        
        return view('ursbid-admin.sub_categories.edit', compact('sub', 'categories', 'categorySlug'));
    }

    /**
     * Update the specified sub category in storage.
     */


    public function update(Request $request, $id)
    {
        // 1) Validate inputs (slug required + regex for hyphenated lowercase)
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|integer',
            'slug'             => ['required','string','max:255','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'order_by'         => 'nullable|integer',
            'status'           => 'required|in:1,2',
            'description'      => 'required|string',
            'tags'             => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'meta_title'       => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
    
        // Normalize slug once (keeps hyphens; enforces lowercase)
        $normalizedSlug = Str::slug($validated['slug']);
    
        // 2) Ensure the sub-category exists
        $sub = DB::table('sub_categories')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
        }
    
        // 3) Duplicate checks within same category (by name and by slug)
        $nameExists = DB::table('sub_categories')
            ->where('category_id', $validated['category_id'])
            ->where('name', $validated['name'])
            ->where('id', '!=', $id)
            ->exists();
    
        if ($nameExists) {
            return response()->json([
                'message' => 'Sub category already exists for this category.',
                'errors'  => ['name' => ['Sub category already exists for this category.']],
            ], 422);
        }
    
        $slugExists = DB::table('sub_categories')
            ->where('category_id', $validated['category_id'])
            ->where('slug', $normalizedSlug)
            ->where('id', '!=', $id)
            ->exists();
    
        if ($slugExists) {
            return response()->json([
                'message' => 'Slug already exists in this category.',
                'errors'  => ['slug' => ['Slug already exists in this category.']],
            ], 422);
        }
    
        // 4) Prepare update payload
        $data = [
            'name'             => $validated['name'],
            'category_id'      => $validated['category_id'],
            'order_by'         => $validated['order_by'] ?? null,
            'status'           => $validated['status'],
            'description'      => $validated['description'],
            'tags'             => isset($validated['tags'])
                                    ? json_encode(array_values(array_filter(array_map('trim', explode(',', $validated['tags'])))))
                                    : json_encode([]),
            'slug'             => $normalizedSlug, // <-- use provided slug (normalized)
            'meta_title'       => $request->input('meta_title'),
            'meta_keywords'    => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'updated_at'       => now(),
        ];
    
        // 5) Handle image upload (delete old if present)
        if ($request->hasFile('image')) {
            if (!empty($sub->image) && file_exists(public_path($sub->image))) {
                @unlink(public_path($sub->image));
            }
            $file = $request->file('image');
            $filename = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
    
            $uploadPath = public_path('uploads/sub_category');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            $file->move($uploadPath, $filename);
            $data['image'] = 'uploads/sub_category/'.$filename;
        }
    
        // 6) Update
        DB::table('sub_categories')->where('id', $id)->update($data);
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Sub category updated successfully.',
        ]);
    }


    /**
     * Toggle the status of the specified sub category.
     */
    public function toggleStatus(Request $request, $id)
    {
        $sub = DB::table('sub_categories')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:1,2',
        ]);

        DB::table('sub_categories')->where('id', $id)->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully.',
        ]);
    }

    /**
     * Remove the specified sub category from storage.
     */
    public function destroy($id)
    {
        $sub = DB::table('sub_categories')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
        }

        if (!empty($sub->image)) {
            $path = public_path('uploads/' . $sub->image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        DB::table('sub_categories')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub category deleted successfully.',
        ]);
    }
}
