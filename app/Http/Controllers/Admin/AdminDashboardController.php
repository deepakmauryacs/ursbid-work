<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
       
        $stats = [
            'monthlyEarnings' => 3548.09,
            'earningsGrowth' => 67435.00,
            'conversionRate' => 78.8,
            'profitMargin' => 34.00
        ];

        return view('ursbid-admin.dashboard', compact('stats'));
    }
}