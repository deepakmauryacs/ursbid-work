<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $products = DB::table('product')
            ->leftJoin('category', 'product.cat_id', '=', 'category.id')
            ->leftJoin('sub', 'product.sub_id', '=', 'sub.id')
            ->select('product.*', 'category.title as category_title', 'sub.title as sub_title')
            ->orderBy('product.order_by')
            ->paginate($perPage);

        return view('ursbid-admin.products.list', compact('products', 'perPage'));
    }

    public function create()
    {
        $categories = DB::table('category')->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.products.create', compact('categories'));
    }

    public function getSubCategories(Request $request)
    {
        $subs = DB::table('sub')
            ->where('cat_id', $request->cat_id)
            ->where('status', 1)
            ->orderBy('title')
            ->get();
        return response()->json($subs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer',
            'sub_id' => 'required|integer',
            'post_date' => 'required|date_format:d-m-Y',
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
            'post_date' => $validated['post_date'],
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
        $categories = DB::table('category')->where('status', 1)->orderBy('title')->get();
        $subCategories = DB::table('sub')->where('cat_id', $product->cat_id)->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.products.edit', compact('product', 'categories', 'subCategories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'cat_id' => 'required|integer',
            'sub_id' => 'required|integer',
            'post_date' => 'required|date_format:d-m-Y',
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
            'post_date' => $validated['post_date'],
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
