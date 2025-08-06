<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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

    public function list(Request $request, string $type)
    {
        $data = $this->getTypeData($type);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
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

        $query = UserAccount::where('user_type', $data['user_type']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
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

        $users = $query->orderByDesc('id')->get();

        $html = view('ursbid-admin.user_accounts.partials.table', [
            'users' => $users,
            'type' => $type,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function create(string $type)
    {
        $data = $this->getTypeData($type);

        return view('ursbid-admin.user_accounts.create', [
            'type' => $type,
            'userType' => $data['label'],
        ]);
    }

    public function store(Request $request, string $type)
    {
        $data = $this->getTypeData($type);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_accounts,email',
            'phone' => 'required|string|max:20|unique:user_accounts,phone',
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
        $validated['user_type'] = $data['user_type'];
        $validated['created_at'] = Carbon::createFromFormat('d-m-Y', $validated['created_at']);
        UserAccount::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully.',
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

        return response()->json([
            'status' => 'success',
            'message' => 'Account updated successfully.',
        ]);
    }
}

