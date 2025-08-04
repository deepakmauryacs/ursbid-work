<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::leftJoin('category', 'unit.cat_id', '=', 'category.id')
            ->leftJoin('sub', 'unit.sub_id', '=', 'sub.id')
            ->select('unit.*', 'category.title as category_title', 'sub.title as sub_title')
            ->orderByDesc('unit.id')
            ->paginate(10);

        return view('ursbid-admin.unit.list', compact('units'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $subs = DB::table('sub')->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.unit.create', compact('categories', 'subs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cat_id' => 'required|integer',
            'sub_id' => 'required|integer',
            'title' => 'required|string|max:255|unique:unit,title,NULL,id,cat_id,' . $request->cat_id . ',sub_id,' . $request->sub_id,
            'status' => 'required|in:1,2',
        ]);

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
            'status' => 'success',
            'message' => 'Unit created successfully.',
            'data' => $unit,
        ]);
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $categories = Category::where('status', 1)->orderBy('title')->get();
        $subs = DB::table('sub')->where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.unit.edit', compact('unit', 'categories', 'subs'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cat_id' => 'required|integer',
            'sub_id' => 'required|integer',
            'title' => 'required|string|max:255|unique:unit,title,' . $unit->id . ',id,cat_id,' . $request->cat_id . ',sub_id,' . $request->sub_id,
            'status' => 'required|in:1,2',
        ]);

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
            'status' => 'success',
            'message' => 'Unit updated successfully.',
            'data' => $unit,
        ]);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit deleted successfully.',
        ]);
    }
}
