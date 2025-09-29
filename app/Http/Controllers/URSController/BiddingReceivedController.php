<?php

namespace App\Http\Controllers\URSController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiddingReceivedController extends Controller
{
    /**
     * Display the bidding received dashboard view.
     */
    public function index(Request $request)
    {
        $seller = $request->session()->get('seller');

        if (!$seller) {
            abort(403, 'Seller session not found.');
        }

        return view('ursdashboard.bidding-received.list', [
            'seller' => $seller,
            'filters' => [],
            'category_data' => collect(),
            'records' => collect(),
        ]);
    }
}
