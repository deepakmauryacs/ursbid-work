<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\UserAccount;
use Carbon\Carbon;

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
            'categories'   => Category::count(),
            'subCategories'=> SubCategory::count(),
            'products'     => Product::count(),
            'vendors'      => UserAccount::where('user_type', 'vendor')->count(),
            'buyers'       => UserAccount::where('user_type', 'buyer')->count(),
            'contractors'  => UserAccount::where('user_type', 'contractor')->count(),
            'clients'      => UserAccount::where('user_type', 'client')->count(),
        ];

        $currentDate = Carbon::now()->format('d-m-Y');

        return view('ursbid-admin.dashboard', compact('stats', 'currentDate'));
    }
}