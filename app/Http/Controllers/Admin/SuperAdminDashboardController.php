<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Seller;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    /**
     * Display the Super Admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'categories'   => Category::count(),
            'subCategories'=> SubCategory::count(),
            'products'     => Product::count(),
            'vendors'      => Seller::where('acc_type', '1')->count(),
            'buyers'       => Seller::where('acc_type', '4')->count(),
            'contractors'  => Seller::where('acc_type', '2')->count(),
            'clients'      => Seller::where('acc_type', '3')->count(),
        ];

        $currentDate = Carbon::now()->format('d-m-Y');

        return view('ursbid-admin.super-admin.dashboard', compact('stats', 'currentDate'));
    }
}
