<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;

class AjexResponseController extends Controller
{
    /**
     * GET /ajax/subcategories?category_id=#
     * Return active sub-categories for a given category.
     */
    public function getSubcategories(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        // Ensure category is active (optional, remove if not needed)
        $category = Category::where('id', $request->category_id)->where('status', '1')->first();
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found or inactive.',
                'data' => [],
            ], 404);
        }

        $subs = SubCategory::where('category_id', $request->category_id)
            ->where('status', '1')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data'   => $subs,
        ]);
    }

    /**
     * GET /ajax/products?sub_category_id=#
     * Return active products for a given sub-category.
     *
     * NOTE: Your `product` table uses `sub_id` (varchar). Some projects store
     * either a single id or comma-separated ids. We support both:
     *  - WHERE sub_id = ?  OR FIND_IN_SET(?, sub_id)
     */
    public function getProducts(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
        ]);

        $subId = (string)$request->sub_category_id;

        $products = Product::query()
            ->where('status', '1')
            ->where(function ($q) use ($subId) {
                $q->where('sub_id', $subId)
                  ->orWhereRaw('FIND_IN_SET(?, sub_id)', [$subId]);
            })
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json([
            'status' => 'success',
            'data'   => $products,
        ]);
    }
}
