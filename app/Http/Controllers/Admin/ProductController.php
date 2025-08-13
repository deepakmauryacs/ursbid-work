<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'    => 'nullable|integer|exists:categories,id',
            'subcategory' => 'nullable|integer|exists:sub_categories,id',
            'name'        => 'nullable|string|max:255',
            'per_page'    => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $perPage = $request->input('per_page', 10);

        $query = DB::table('product')
            ->leftJoin('categories', 'product.cat_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'product.sub_id', '=', 'sub_categories.id')
            ->select('product.*', 'categories.name as category_name', 'sub_categories.name as sub_name')
            ->orderBy('product.order_by');

        if ($request->filled('category')) {
            $query->where('product.cat_id', $request->category);
        }
        if ($request->filled('subcategory')) {
            $query->where('product.sub_id', $request->subcategory);
        }
        if ($request->filled('name')) {
            $query->where('product.title', 'like', '%' . $request->name . '%');
        }

        $products = $query->paginate($perPage)->appends($request->all());
        $categories = DB::table('categories')->where('status', 1)->orderBy('name')->get();

        if ($request->ajax()) {
            return view('ursbid-admin.products.partials.table', compact('products'))->render();
        }

        return view('ursbid-admin.products.list', compact('products', 'categories', 'perPage'));
    }



    public function create()
    {
        $categories = DB::table('categories')->where('status', 1)->orderBy('name')->get();
        return view('ursbid-admin.products.create', compact('categories'));
    }

    public function getSubCategories(Request $request)
    {
        $request->validate([
            'cat_id' => 'required|integer|exists:categories,id',
        ]);

        $subs = DB::table('sub_categories')
            ->where('category_id', $request->cat_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        return response()->json($subs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer|exists:categories,id',
            'sub_id' => 'required|integer|exists:sub_categories,id',
            'order_by' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $exists = DB::table('product')
            ->where('cat_id', $validated['cat_id'])
            ->where('sub_id', $validated['sub_id'])
            ->where('title', $validated['title'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Product already exists for this sub category.',
                'errors' => ['title' => ['Product already exists for this sub category.']],
            ], 422);
        }

        $data = [
            'title' => $validated['title'],
            'cat_id' => $validated['cat_id'],
            'sub_id' => $validated['sub_id'],
            'order_by' => $validated['order_by'],
            'status' => $validated['status'],
            'description' => $request->description,
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'user_id' => 0,
            'user_type' => 'Admin',
            'insert_by' => 'Admin',
            'update_by' => 'Admin',
        ];

        $id = DB::table('product')->insertGetId($data);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('uploads/product');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            DB::table('product')->where('id', $id)->update([
                'image' => 'uploads/product/' . $filename,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully.',
        ]);
    }

    public function edit($id)
    {
        $product = DB::table('product')->where('id', $id)->first();
        if (!$product) {
            abort(404);
        }
        $categories = DB::table('categories')->where('status', 1)->orderBy('name')->get();
        $subCategories = DB::table('sub_categories')->where('category_id', $product->cat_id)->where('status', 1)->orderBy('name')->get();
        return view('ursbid-admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer|exists:categories,id',
            'sub_id' => 'required|integer|exists:sub_categories,id',
            'order_by' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $exists = DB::table('product')
            ->where('cat_id', $validated['cat_id'])
            ->where('sub_id', $validated['sub_id'])
            ->where('title', $validated['title'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Product already exists for this sub category.',
                'errors' => ['title' => ['Product already exists for this sub category.']],
            ], 422);
        }

        $product = DB::table('product')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $data = [
            'title' => $validated['title'],
            'cat_id' => $validated['cat_id'],
            'sub_id' => $validated['sub_id'],
            'order_by' => $validated['order_by'],
            'status' => $validated['status'],
            'description' => $request->description,
            'slug' => Str::slug($validated['title']),
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'update_by' => 'Admin',
        ];

        if ($request->hasFile('image')) {
            if (!empty($product->image) && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('uploads/product');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $data['image'] = 'uploads/product/' . $filename;
        }

        DB::table('product')->where('id', $id)->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $product = DB::table('product')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        if (!empty($product->image) && file_exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        DB::table('product')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
        ]);
    }
}
