<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SuperAdminDashboardController;
use App\Http\Controllers\Admin\WebSettingsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OnPageSeoController;
use App\Http\Controllers\Admin\YoutubeLinkController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\UserAccountController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UsermanagementController;
use App\Http\Controllers\Admin\SupportTicketController as AdminSupportTicketController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Frontend\AjexResponseController;

// Super Admin Auth
Route::get('super-admin', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('super-admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::any('super-admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('super-admin/profile', [ProfileController::class, 'edit'])->name('super-admin.profile.edit');
Route::put('super-admin/profile', [ProfileController::class, 'update'])->name('super-admin.profile.update');
Route::get('super-admin/change-password', [ProfileController::class, 'editPassword'])->name('super-admin.password.edit');
Route::put('super-admin/change-password', [ProfileController::class, 'updatePassword'])->name('super-admin.password.update');


// Super Admin Panel Routes
Route::prefix('super-admin')->middleware('SuperAdmin')->group(function () {
    // Dashboard
    Route::get('dashboard', [SuperAdminDashboardController::class, 'index'])->name('super-admin.dashboard');

    // Web Settings
    Route::get('web-settings', [WebSettingsController::class, 'edit'])->name('super-admin.web-settings.edit');
    Route::post('web-settings/save', [WebSettingsController::class, 'save'])->name('super-admin.web-settings.save');

    // Help & Support
    Route::get('help-support', [AdminSupportTicketController::class, 'index'])
        ->name('super-admin.support-tickets.index')
        ->middleware('module.permission:help-support,can_view');
    Route::get('help-support/{ticket}', [AdminSupportTicketController::class, 'show'])
        ->name('super-admin.support-tickets.show')
        ->middleware('module.permission:help-support,can_view');
    Route::patch('help-support/{ticket}/status', [AdminSupportTicketController::class, 'updateStatus'])
        ->name('super-admin.support-tickets.update-status')
        ->middleware('module.permission:help-support,can_edit');

    // Categories
    Route::get('categories', [AdminCategoryController::class, 'index'])
        ->name('super-admin.categories.index')
        ->middleware('module.permission:categories,can_view');
    Route::get('categories/create', [AdminCategoryController::class, 'create'])
        ->name('super-admin.categories.create')
        ->middleware('module.permission:categories,can_add');
    Route::post('categories', [AdminCategoryController::class, 'store'])
        ->name('super-admin.categories.store')
        ->middleware('module.permission:categories,can_add');
    Route::get('categories/{id}/edit', [AdminCategoryController::class, 'edit'])
        ->name('super-admin.categories.edit')
        ->middleware('module.permission:categories,can_edit');
    Route::put('categories/{id}', [AdminCategoryController::class, 'update'])
        ->name('super-admin.categories.update')
        ->middleware('module.permission:categories,can_edit');
    Route::patch('categories/{id}/status', [AdminCategoryController::class, 'toggleStatus'])
        ->name('super-admin.categories.toggle-status')
        ->middleware('module.permission:categories,can_edit');
    Route::delete('categories/{id}', [AdminCategoryController::class, 'destroy'])
        ->name('super-admin.categories.destroy')
        ->middleware('module.permission:categories,can_delete');

    
    // Sub-Categories
    Route::get('sub-categories', [SubCategoryController::class, 'index'])
        ->name('super-admin.sub-categories.index')
        ->middleware('module.permission:sub-categories,can_view');
    Route::get('sub-categories/create', [SubCategoryController::class, 'create'])
        ->name('super-admin.sub-categories.create')
        ->middleware('module.permission:sub-categories,can_add');
    Route::post('sub-categories', [SubCategoryController::class, 'store'])
        ->name('super-admin.sub-categories.store')
        ->middleware('module.permission:sub-categories,can_add');
    Route::get('sub-categories/{id}/edit', [SubCategoryController::class, 'edit'])
        ->name('super-admin.sub-categories.edit')
        ->middleware('module.permission:sub-categories,can_edit');
    Route::post('sub-categories/{id}', [SubCategoryController::class, 'update'])
        ->name('super-admin.sub-categories.update')
        ->middleware('module.permission:sub-categories,can_edit');
    Route::patch('sub-categories/{id}/status', [SubCategoryController::class, 'toggleStatus'])
    ->name('super-admin.sub-categories.toggle-status')
    ->middleware('module.permission:sub-categories,can_edit');
    Route::delete('sub-categories/{id}', [SubCategoryController::class, 'destroy'])
        ->name('super-admin.sub-categories.destroy')
        ->middleware('module.permission:sub-categories,can_delete');
    

    // Blogs
    Route::get('blogs', [AdminBlogController::class, 'index'])
        ->name('super-admin.blogs.index')
        ->middleware('module.permission:blogs,can_view');
    Route::get('blogs/create', [AdminBlogController::class, 'create'])
        ->name('super-admin.blogs.create')
        ->middleware('module.permission:blogs,can_add');
    Route::post('blogs', [AdminBlogController::class, 'store'])
        ->name('super-admin.blogs.store')
        ->middleware('module.permission:blogs,can_add');
    Route::get('blogs/{id}/edit', [AdminBlogController::class, 'edit'])
        ->name('super-admin.blogs.edit')
        ->middleware('module.permission:blogs,can_edit');
    Route::post('blogs/{id}', [AdminBlogController::class, 'update'])
        ->name('super-admin.blogs.update')
        ->middleware('module.permission:blogs,can_edit');
    Route::delete('blogs/{id}', [AdminBlogController::class, 'destroy'])
        ->name('super-admin.blogs.destroy')
        ->middleware('module.permission:blogs,can_delete');
    Route::patch('blogs/{id}/status', [AdminBlogController::class, 'toggleStatus'])
    ->name('super-admin.blogs.toggle-status')
    ->middleware('module.permission:blogs,can_edit'); 
   

    // Products
    Route::get('products', [AdminProductController::class, 'index'])
        ->name('super-admin.products.index')
        ->middleware('module.permission:products,can_view');
    Route::get('products/create', [AdminProductController::class, 'create'])
        ->name('super-admin.products.create')
        ->middleware('module.permission:products,can_add');
    Route::post('products', [AdminProductController::class, 'store'])
        ->name('super-admin.products.store')
        ->middleware('module.permission:products,can_add');
    Route::get('products/{id}/edit', [AdminProductController::class, 'edit'])
        ->name('super-admin.products.edit')
        ->middleware('module.permission:products,can_edit');
    Route::post('products/{id}', [AdminProductController::class, 'update'])
        ->name('super-admin.products.update')
        ->middleware('module.permission:products,can_edit');
    Route::delete('products/{id}', [AdminProductController::class, 'destroy'])
        ->name('super-admin.products.destroy')
        ->middleware('module.permission:products,can_delete');
    Route::get('get-subcategories', [AdminProductController::class, 'getSubCategories'])
        ->name('super-admin.products.get-subcategories')
        ->middleware('module.permission:products,can_view');

    // Units
    Route::get('units', [UnitController::class, 'index'])
        ->name('super-admin.units.index')
        ->middleware('module.permission:units,can_view');
    Route::get('units/create', [UnitController::class, 'create'])
        ->name('super-admin.units.create')
        ->middleware('module.permission:units,can_add');
    Route::post('units', [UnitController::class, 'store'])
        ->name('super-admin.units.store')
        ->middleware('module.permission:units,can_add');
    Route::get('units/{id}/edit', [UnitController::class, 'edit'])
        ->name('super-admin.units.edit')
        ->middleware('module.permission:units,can_edit');
    Route::put('units/{id}', [UnitController::class, 'update'])
        ->name('super-admin.units.update')
        ->middleware('module.permission:units,can_edit');
    Route::delete('units/{id}', [UnitController::class, 'destroy'])
        ->name('super-admin.units.destroy')
        ->middleware('module.permission:units,can_delete');
    
    // On-Page SEO
    Route::get('on-page-seo', [OnPageSeoController::class, 'index'])
    ->name('super-admin.on-page-seo.index')
    ->middleware('module.permission:on-page-seo,can_view');
    Route::get('on-page-seo/create', [OnPageSeoController::class, 'create'])
        ->name('super-admin.on-page-seo.create')
        ->middleware('module.permission:on-page-seo,can_add');
    Route::post('on-page-seo', [OnPageSeoController::class, 'store'])
        ->name('super-admin.on-page-seo.store')
        ->middleware('module.permission:on-page-seo,can_add');
    Route::get('on-page-seo/{id}/edit', [OnPageSeoController::class, 'edit'])
        ->name('super-admin.on-page-seo.edit')
        ->middleware('module.permission:on-page-seo,can_edit');
    Route::post('on-page-seo/{id}', [OnPageSeoController::class, 'update'])
        ->name('super-admin.on-page-seo.update')
        ->middleware('module.permission:on-page-seo,can_edit'); 
    Route::delete('on-page-seo/{id}', [OnPageSeoController::class, 'destroy'])
        ->name('super-admin.on-page-seo.destroy')
        ->middleware('module.permission:on-page-seo,can_delete');

    // Youtube Links
    Route::get('youtube-links', [YoutubeLinkController::class, 'index'])
      ->name('super-admin.youtube-links.index')
      ->middleware('module.permission:youtube-links,can_view');
    Route::get('youtube-links/create', [YoutubeLinkController::class, 'create'])
        ->name('super-admin.youtube-links.create')
        ->middleware('module.permission:youtube-links,can_add');
    Route::post('youtube-links', [YoutubeLinkController::class, 'store'])
        ->name('super-admin.youtube-links.store')
        ->middleware('module.permission:youtube-links,can_add');
    Route::get('youtube-links/{id}/edit', [YoutubeLinkController::class, 'edit'])
        ->name('super-admin.youtube-links.edit')
        ->middleware('module.permission:youtube-links,can_edit');
    Route::post('youtube-links/{id}', [YoutubeLinkController::class, 'update'])
        ->name('super-admin.youtube-links.update')
        ->middleware('module.permission:youtube-links,can_edit');
    Route::delete('youtube-links/{id}', [YoutubeLinkController::class, 'destroy'])
        ->name('super-admin.youtube-links.destroy')
        ->middleware('module.permission:youtube-links,can_delete');
        

    // Testimonials
    Route::get('testimonials', [AdminTestimonialController::class, 'index'])
         ->name('super-admin.testimonials.index')
         ->middleware('module.permission:testimonials,can_view');
    Route::get('testimonials/create', [AdminTestimonialController::class, 'create'])
        ->name('super-admin.testimonials.create')
        ->middleware('module.permission:testimonials,can_add');
    Route::post('testimonials', [AdminTestimonialController::class, 'store'])
        ->name('super-admin.testimonials.store')
        ->middleware('module.permission:testimonials,can_add');
    Route::get('testimonials/{id}/edit', [AdminTestimonialController::class, 'edit'])
        ->name('super-admin.testimonials.edit')
        ->middleware('module.permission:testimonials,can_edit');
    Route::post('testimonials/{id}', [AdminTestimonialController::class, 'update'])
        ->name('super-admin.testimonials.update')
        ->middleware('module.permission:testimonials,can_edit');
    Route::delete('testimonials/{id}', [AdminTestimonialController::class, 'destroy'])
        ->name('super-admin.testimonials.destroy')
        ->middleware('module.permission:testimonials,can_delete');
    

    // Product Brands
    Route::get('product-brands', [ProductBrandController::class, 'index'])
          ->name('super-admin.product-brands.index')
          ->middleware('module.permission:product-brands,can_view');
    Route::get('product-brands/create', [ProductBrandController::class, 'create'])
        ->name('super-admin.product-brands.create')
        ->middleware('module.permission:product-brands,can_add');
    Route::post('product-brands', [ProductBrandController::class, 'store'])
        ->name('super-admin.product-brands.store')
        ->middleware('module.permission:product-brands,can_add');
    Route::get('product-brands/{id}/edit', [ProductBrandController::class, 'edit'])
        ->name('super-admin.product-brands.edit')
        ->middleware('module.permission:product-brands,can_edit');
    Route::put('product-brands/{id}', [ProductBrandController::class, 'update'])
        ->name('super-admin.product-brands.update')
        ->middleware('module.permission:product-brands,can_edit');
    Route::delete('product-brands/{id}', [ProductBrandController::class, 'destroy'])
        ->name('super-admin.product-brands.destroy')
        ->middleware('module.permission:product-brands,can_delete');
    
   
    // Advertisements
    Route::get('advertisements', [AdvertisementController::class, 'index'])
          ->name('super-admin.advertisements.index')
          ->middleware('module.permission:advertisements,can_view');
    Route::get('advertisements/create', [AdvertisementController::class, 'create'])
        ->name('super-admin.advertisements.create')
        ->middleware('module.permission:advertisements,can_add');
    Route::post('advertisements', [AdvertisementController::class, 'store'])
        ->name('super-admin.advertisements.store')
        ->middleware('module.permission:advertisements,can_add');
    Route::get('advertisements/{id}/edit', [AdvertisementController::class, 'edit'])
        ->name('super-admin.advertisements.edit')
        ->middleware('module.permission:advertisements,can_edit');
    Route::put('advertisements/{id}', [AdvertisementController::class, 'update'])
        ->name('super-admin.advertisements.update')
        ->middleware('module.permission:advertisements,can_edit');
    Route::delete('advertisements/{id}', [AdvertisementController::class, 'destroy'])
        ->name('super-admin.advertisements.destroy')
        ->middleware('module.permission:advertisements,can_delete');

    // Accounts
    Route::prefix('accounts')->name('super-admin.accounts.')->group(function () {
        Route::get('{type}', [UserAccountController::class, 'index'])
        ->name('index')
        ->middleware('module.permission:accounts,can_view');
        Route::get('{type}/list', [UserAccountController::class, 'list'])
            ->name('list')
            ->middleware('module.permission:accounts,can_view');
        Route::get('{type}/create', [UserAccountController::class, 'create'])
            ->name('create')
            ->middleware('module.permission:accounts,can_add');
        Route::post('{type}', [UserAccountController::class, 'store'])
            ->name('store')
            ->middleware('module.permission:accounts,can_add');
        Route::get('{type}/{id}/edit', [UserAccountController::class, 'edit'])
            ->name('edit')
            ->middleware('module.permission:accounts,can_edit');
        Route::get('{type}/{id}', [UserAccountController::class, 'show'])
            ->name('show')
            ->middleware('module.permission:accounts,can_view');
        Route::put('{type}/{id}', [UserAccountController::class, 'update'])
            ->name('update')
            ->middleware('module.permission:accounts,can_edit');
    });

    // Roles & Permissions
    Route::prefix('roles')->name('super-admin.roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
        ->name('index')
        ->middleware('module.permission:roles,can_view');
        Route::get('/list', [RoleController::class, 'list'])
            ->name('list')
            ->middleware('module.permission:roles,can_view');
        Route::get('/create', [RoleController::class, 'create'])
            ->name('create')
            ->middleware('module.permission:roles,can_add');
        Route::post('/', [RoleController::class, 'store'])
            ->name('store')
            ->middleware('module.permission:roles,can_add');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->name('edit')
            ->middleware('module.permission:roles,can_edit');
        Route::put('/{id}', [RoleController::class, 'update'])
            ->name('update')
            ->middleware('module.permission:roles,can_edit');
        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->name('destroy')
            ->middleware('module.permission:roles,can_delete');
        Route::get('/{id}/permissions', [RolePermissionController::class, 'edit'])
            ->name('permissions.edit')
            ->middleware('module.permission:roles,can_edit');
        Route::post('/{id}/permissions', [RolePermissionController::class, 'update'])
            ->name('permissions.update')
            ->middleware('module.permission:roles,can_edit');
    });

    // User Management
    Route::prefix('user-management')->name('super-admin.user-management.')->group(function () {
        Route::get('/', [UsermanagementController::class, 'index'])
        ->name('index')
        ->middleware('module.permission:user-management,can_view');
        Route::get('/list', [UsermanagementController::class, 'list'])
            ->name('list')
            ->middleware('module.permission:user-management,can_view');
        Route::get('/create', [UsermanagementController::class, 'create'])
            ->name('create')
            ->middleware('module.permission:user-management,can_add');
        Route::post('/', [UsermanagementController::class, 'store'])
            ->name('store')
            ->middleware('module.permission:user-management,can_add');
        Route::get('/{id}/edit', [UsermanagementController::class, 'edit'])
            ->name('edit')
            ->middleware('module.permission:user-management,can_edit');
        Route::put('/{id}', [UsermanagementController::class, 'update'])
            ->name('update')
            ->middleware('module.permission:user-management,can_edit');
        Route::delete('/{id}', [UsermanagementController::class, 'destroy'])
            ->name('destroy')
            ->middleware('module.permission:user-management,can_delete');
    });

    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('subcategories', [AjexResponseController::class, 'getSubcategories'])
            ->name('subcategories');

        Route::get('products', [AjexResponseController::class, 'getProducts'])
            ->name('products');
    });
    
    
});
