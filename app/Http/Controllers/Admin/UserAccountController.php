<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAccountController extends Controller
{
    protected array $types = [
        'vendors' => ['user_type' => 'vendor', 'label' => 'Vendor'],
        'buyers' => ['user_type' => 'buyer', 'label' => 'Buyer'],
        'contractors' => ['user_type' => 'contractor', 'label' => 'Contractor'],
        'clients' => ['user_type' => 'client', 'label' => 'Client'],
    ];

    protected function getTypeData(string $type): array
    {
        if (!isset($this->types[$type])) {
            abort(404);
        }

        return $this->types[$type];
    }

    public function index(string $type)
    {
        $data = $this->getTypeData($type);

        return view('ursbid-admin.user_accounts.index', [
            'type' => $type,
            'userType' => $data['label'],
        ]);
    }

    public function list(string $type)
    {
        $data = $this->getTypeData($type);
        $users = UserAccount::where('user_type', $data['user_type'])
            ->orderByDesc('id')
            ->get();

        $html = view('ursbid-admin.user_accounts.partials.table', [
            'users' => $users,
            'type' => $type,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function edit(string $type, int $id)
    {
        $data = $this->getTypeData($type);
        $user = UserAccount::where('user_type', $data['user_type'])->findOrFail($id);

        return view('ursbid-admin.user_accounts.edit', [
            'user' => $user,
            'type' => $type,
            'userType' => $data['label'],
        ]);
    }

    public function show(string $type, int $id)
    {
        $data = $this->getTypeData($type);
        $user = UserAccount::where('user_type', $data['user_type'])->findOrFail($id);

        return view('ursbid-admin.user_accounts.show', [
            'user' => $user,
            'type' => $type,
            'userType' => $data['label'],
        ]);
    }

    public function update(Request $request, string $type, int $id)
    {
        $data = $this->getTypeData($type);
        $user = UserAccount::where('user_type', $data['user_type'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_accounts,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:user_accounts,phone,' . $user->id,
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully.',
        ]);
    }
}

