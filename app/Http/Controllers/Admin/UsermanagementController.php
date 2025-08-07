<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UsermanagementController extends Controller
{
    public function index()
    {
        return view('ursbid-admin.user_management.index');
    }

    public function list()
    {
        $users = User::with('roles')->orderByDesc('id')->get();
        $html = view('ursbid-admin.user_management.partials.table', [
            'users' => $users,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function create()
    {
        return view('ursbid-admin.user_management.create', [
            'roles' => Role::orderBy('role_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_type' => 'required|in:1,2',
            'address' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'created_at' => 'required|date_format:d-m-Y',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $validated['parent_id'] = auth()->id() ?? 0;
        $validated['password'] = bcrypt('password');
        $validated['created_at'] = Carbon::createFromFormat('d-m-Y', $validated['created_at']);

        $user = User::create($validated);

        if ($request->filled('roles')) {
            $user->roles()->sync($request->roles);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
        ]);
    }

    public function edit(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('ursbid-admin.user_management.edit', [
            'user' => $user,
            'roles' => Role::orderBy('role_name')->get(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required|in:1,2',
            'address' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
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
        $user->update($validated);

        if ($request->filled('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}

