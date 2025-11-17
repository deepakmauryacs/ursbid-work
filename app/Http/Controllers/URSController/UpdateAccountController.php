<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateAccountController extends Controller
{
    public function show(Request $request, $hashId)
    {
        $seller = $request->session()->get('seller');

        if (! $seller) {
            return redirect()->route('seller-login');
        }

        $account = DB::table('seller')
            ->where('hash_id', $hashId)
            ->first();

        if (! $account) {
            abort(404, 'Account not found.');
        }

        return view('ursdashboard.setting.updated-account.index', [
            'account' => $account,
        ]);
    }
}
