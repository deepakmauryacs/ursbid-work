<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::leftJoin('category', 'advertisements.category_id', '=', 'category.id')
            ->select('advertisements.*', 'category.title as category_title')
            ->orderByDesc('advertisements.id')
            ->paginate(10);

        return view('ursbid-admin.advertisement.list', compact('advertisements'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.advertisement.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/advertisements'), $filename);
            $data['image'] = 'uploads/advertisements/' . $filename;
        }

        $advertisement = Advertisement::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Advertisement created successfully.',
            'data' => $advertisement,
        ]);
    }

    public function edit($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        $categories = Category::where('status', 1)->orderBy('title')->get();
        return view('ursbid-admin.advertisement.edit', compact('advertisement', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($advertisement->image && file_exists(public_path($advertisement->image))) {
                @unlink(public_path($advertisement->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/advertisements'), $filename);
            $data['image'] = 'uploads/advertisements/' . $filename;
        }

        $advertisement->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Advertisement updated successfully.',
            'data' => $advertisement,
        ]);
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        if ($advertisement->image && file_exists(public_path($advertisement->image))) {
            @unlink(public_path($advertisement->image));
        }
        $advertisement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Advertisement deleted successfully.',
        ]);
    }
}
