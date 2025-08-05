<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductBrandController extends Controller
{
    public function index()
    {
        $brands = ProductBrand::leftJoin('category', 'product_brands.category_id', '=', 'category.id')
            ->leftJoin('sub', 'product_brands.sub_category_id', '=', 'sub.id')
            ->leftJoin('product', 'product_brands.product_id', '=', 'product.id')
            ->select('product_brands.*', 'category.title as category_title', 'sub.title as sub_title', 'product.title as product_title')
            ->orderByDesc('product_brands.id')
            ->paginate(10);

        return view('ursbid-admin.product_brands.list', compact('brands'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $subs = DB::table('sub')->where('status', 1)->orderBy('title')->get();
        $products = Product::where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.product_brands.create', compact('categories', 'subs', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'product_id' => 'required|integer',
            'brand_name' => 'required|string|max:255|unique:product_brands,brand_name,NULL,id,category_id,' . $request->category_id . ',sub_category_id,' . $request->sub_category_id . ',product_id,' . $request->product_id,
            'description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['brand_name']);

        $brand = ProductBrand::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Brand created successfully.',
            'data' => $brand,
        ]);
    }

    public function edit($id)
    {
        $brand = ProductBrand::findOrFail($id);
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $subs = DB::table('sub')->where('status', 1)->orderBy('title')->get();
        $products = Product::where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.product_brands.edit', compact('brand', 'categories', 'subs', 'products'));
    }

    public function update(Request $request, $id)
    {
        $brand = ProductBrand::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'product_id' => 'required|integer',
            'brand_name' => 'required|string|max:255|unique:product_brands,brand_name,' . $brand->id . ',id,category_id,' . $request->category_id . ',sub_category_id,' . $request->sub_category_id . ',product_id,' . $request->product_id,
            'description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['brand_name']);

        $brand->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Brand updated successfully.',
            'data' => $brand,
        ]);
    }

    public function destroy($id)
    {
        $brand = ProductBrand::findOrFail($id);
        $brand->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Brand deleted successfully.',
        ]);
    }
}
