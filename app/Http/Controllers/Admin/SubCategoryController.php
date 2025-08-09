<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the sub categories.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'  => 'nullable|integer|exists:categories,id',
            'name'      => 'nullable|string|max:255',
            'from_date' => 'nullable|date_format:d-m-Y',
            'to_date'   => 'nullable|date_format:d-m-Y|after_or_equal:from_date',
            'per_page'  => 'nullable|integer',
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

        if ($request->filled('from_date')) {
            $from = Carbon::createFromFormat('d-m-Y', $request->from_date)->startOfDay();
            $query->whereDate('sub_categories.created_at', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = Carbon::createFromFormat('d-m-Y', $request->to_date)->endOfDay();
            $query->whereDate('sub_categories.created_at', '<=', $to);
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
        return view('ursbid-admin.sub_categories.edit', compact('sub', 'categories'));
    }

    /**
     * Update the specified sub category in storage.
     */
    public function update(Request $request, $id)
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
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sub category already exists for this category.',
                'errors' => ['name' => ['Sub category already exists for this category.']],
            ], 422);
        }

        $sub = DB::table('sub_categories')->where('id', $id)->first();
        if (!$sub) {
            return response()->json(['message' => 'Sub category not found.'], 404);
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

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($sub->image) && file_exists(public_path($sub->image))) {
                @unlink(public_path($sub->image));
            }
        
            // Upload new image
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
        
            // Create folder if not exists
            $uploadPath = public_path('uploads/sub_category');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
        
            // Move file
            $file->move($uploadPath, $filename);
        
            // Save path in database
            $data['image'] = 'uploads/sub_category/' . $filename;
        }


        DB::table('sub_categories')->where('id', $id)->update($data);

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
