<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function index()
    {
        return view('admin.account.index');
    }

    public function list($type)
    {
        $accounts = UserAccount::where('user_type', $type)
            ->select('id', 'name', 'email', 'phone', 'user_type', 'created_at')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                $item->created_at_formatted = $item->created_at ? $item->created_at->format('d-m-Y') : null;
                return $item;
            });
        return response()->json($accounts);
    }

    public function show($id)
    {
        $account = UserAccount::findOrFail($id);
        $account->created_at_formatted = $account->created_at ? $account->created_at->format('d-m-Y') : null;
        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $account = UserAccount::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user_accounts,email,' . $id,
            'phone' => 'required|string|max:20|unique:user_accounts,phone,' . $id,
        ]);
        $account->update($validated);
        return response()->json(['success' => true, 'user_type' => $account->user_type]);
    }
}

