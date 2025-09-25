<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Unit;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::query()
            ->leftJoin('categories', 'unit.category_id', '=', 'categories.id')
            ->leftJoin('sub_categories', 'unit.sub_category_id', '=', 'sub_categories.id')
            ->select([
                'unit.*',
                'categories.name as category_name',
                'sub_categories.name as sub_name',
            ])
            ->orderByDesc('unit.id')
            ->paginate(25);

        return view('ursbid-admin.unit.list', compact('units'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subs       = SubCategory::where('status', 1)->orderBy('name')->get();

        return view('ursbid-admin.unit.create', compact('categories', 'subs'));
    }

    public function store(Request $request)
    {
        // Back-compat with old field names: cat_id / sub_id
        $categoryId    = $request->input('category_id', $request->input('cat_id'));
        $subCategoryId = $request->input('sub_category_id', $request->input('sub_id'));

        $validator = Validator::make(
            array_merge($request->all(), [
                'category_id'     => $categoryId,
                'sub_category_id' => $subCategoryId,
            ]),
            [
                'category_id'     => ['required'],
                'sub_category_id' => ['required'],
                'title'           => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('unit', 'title')
                        ->where('category_id', $categoryId)
                        ->where('sub_category_id', $subCategoryId),
                ],
                'status'          => ['required', Rule::in([1, 2, '1', '2'])],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['title']);

        $unit = Unit::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Unit created successfully.',
            'data'    => $unit,
        ]);
    }

    public function edit($id)
    {
        $unit       = Unit::findOrFail($id);
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subs       = SubCategory::where('status', 1)->orderBy('name')->get();

        return view('ursbid-admin.unit.edit', compact('unit', 'categories', 'subs'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        // Back-compat with old field names: cat_id / sub_id
        $categoryId    = $request->input('category_id', $request->input('cat_id'));
        $subCategoryId = $request->input('sub_category_id', $request->input('sub_id'));

        $validator = Validator::make(
            array_merge($request->all(), [
                'category_id'     => $categoryId,
                'sub_category_id' => $subCategoryId,
            ]),
            [
                'category_id'     => ['required', 'integer'],
                'sub_category_id' => ['required', 'integer'],
                'title'           => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('unit', 'title')
                        ->ignore($unit->id) // ignore current row
                        ->where('category_id', $categoryId)
                        ->where('sub_category_id', $subCategoryId),
                ],
                'status'          => ['required', Rule::in([1, 2, '1', '2'])],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['title']);

        $unit->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Unit updated successfully.',
            'data'    => $unit,
        ]);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Unit deleted successfully.',
        ]);
    }
}
