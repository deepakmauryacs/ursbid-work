<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RoleController extends Controller
{
    public function index()
    {
        return view('ursbid-admin.roles.index');
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'nullable|string|max:100',
            'status' => 'nullable|in:1,2',
            'from_date' => 'nullable|date_format:d-m-Y',
            'to_date' => 'nullable|date_format:d-m-Y',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = Role::query();

        if ($request->filled('role_name')) {
            $query->where('role_name', 'like', '%' . $request->role_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::createFromFormat('d-m-Y', $request->from_date)->format('Y-m-d'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::createFromFormat('d-m-Y', $request->to_date)->format('Y-m-d'));
        }

        $roles = $query->orderByDesc('id')->get();
        $html = view('ursbid-admin.roles.partials.table', [
            'roles' => $roles,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function create()
    {
        return view('ursbid-admin.roles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:100',
            'status' => 'required|in:1,2',
            'created_at' => 'required|date_format:d-m-Y',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $validated['created_at'] = Carbon::createFromFormat('d-m-Y', $validated['created_at']);
        Role::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.',
        ]);
    }
}
