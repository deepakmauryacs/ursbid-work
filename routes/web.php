<?php
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\WebSettingsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route to display the email form
Route::get('/test', [EmailController::class, 'showForm'])->name('email.form');

// Route to handle the form submission
Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('email.send');


Route::get('/', function () {return view('frontend/home');});  
Route::get('/advertise', function () {return view('frontend/advertise');});
Route::get('/about', function () {return view('frontend/about');});
Route::get('/terms-conditions', function () {return view('frontend/terms-conditions');});
Route::get('/accept-terms-condition', function () {return view('frontend/accept-terms-condition');});
Route::get('/disclaimer', function () {return view('frontend/disclaimer');});
Route::get('/privacy-policy', function () {return view('frontend/privacy-policy');});
Route::get('/admin-login', function () {return view('frontend/admin-login');});
Route::get('/buyer-register', function () {return view('frontend/buyer-register');});
Route::get('/buyer-login', function () {return view('frontend/buyer-login');});
Route::get('/contact-detail', function () {return view('frontend/contact-detail');});
Route::get('/customer-support', function () {return view('frontend/customer-support');});
Route::get('/filter/{cat_id}/{sub_id}', [App\Http\Controllers\HomeController::class, 'filter']);
Route::get('/product/{cat_id}/{sub_id}/{sup_id}', [App\Http\Controllers\HomeController::class, 'product']);
Route::get('/product-detail/{any}', [App\Http\Controllers\HomeController::class, 'product_detail']);
Route::get('/productdetailsearch/{any}', [App\Http\Controllers\HomeController::class, 'productdetailsearch']);
Route::get('/seller-profile/{any}', [App\Http\Controllers\BuyerloginController::class, 'seller_profile']);
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search']);
Route::post('contact_inc', [App\Http\Controllers\HomeController::class, 'contact_inc'])->name('contact_inc');
Route::post('advertise_inc', [App\Http\Controllers\HomeController::class, 'advertise_inc'])->name('advertise_inc');
Route::post('support_inc', [App\Http\Controllers\HomeController::class, 'support_inc'])->name('support_inc');

Route::get('/menu', function () {return view('frontend/menu');});
// Route::get('/product-detail', function () {return view('frontend/product-detail');});
// Route::get('/seller-login', function () {return view('frontend/seller-login');});
// Route::get('/seller-register', function () {return view('frontend/seller-register');});
Route::get('/autofill-address', [App\Http\Controllers\HomeController::class, 'autofillAddress']);

Route::post('qutation_form', [App\Http\Controllers\HomeController::class, 'qutation_form'])->name('qutation_form');

Route::post('rating_new', [App\Http\Controllers\HomeController::class, 'rating'])->name('rating');
Route::post('/fetch-options', [App\Http\Controllers\HomeController::class, 'fetchOptions'])->name('fetch.options');
Route::post('/fetch-optionsback', [App\Http\Controllers\HomeController::class, 'fetchOptionsback'])->name('fetch.optionsback');


// forget pass
// Route::get('/forgot-password', [App\Http\Controllers\HomeController::class, 'forgot_password']);
// Route::post('/forgot-password', [App\Http\Controllers\HomeController::class, 'cforgot_password']);
// Route::post('/c-up-password/{any}', [App\Http\Controllers\HomeController::class, 'cfupdate_password']);
// Route::get('/update-pass', [App\Http\Controllers\HomeController::class, 'c_update']);

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('forgot.form');
Route::post('/forgot-password', [PasswordResetController::class, 'submitForgotForm'])->name('forgot.submit');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('reset.form');
Route::post('/reset-password', [PasswordResetController::class, 'submitResetForm'])->name('reset.submit');




// Seller ---------------------------------------
Route::get('refer-register/{id}', [App\Http\Controllers\SellerloginController::class, 'refer_register'])->name('refer-register');
Route::get('seller-register', [App\Http\Controllers\SellerloginController::class, 'seller_register'])->name('seller-register');
Route::post('seller-register', [App\Http\Controllers\SellerloginController::class, 'seller_create']);
Route::get('seller-login', [App\Http\Controllers\SellerloginController::class, 'seller_login'])->name('seller-login');
Route::post('seller-login', [App\Http\Controllers\SellerloginController::class, 'authenticate'])->name('seller-login');

Route::post('seller-login-form', [App\Http\Controllers\SellerloginController::class, 'authenticate2'])->name('seller-login-form');


Route::get('/verify-otp/{hash_id}', [App\Http\Controllers\SellerloginController::class, 'showOtpVerificationForm'])->name('verify.otp');

Route::post('/verify-acc', [App\Http\Controllers\SellerloginController::class, 'verifyOtp'])->name('verify-acc');


Route::group(['middleware' => 'seller_auth'], function()
{ 
    

Route::get('/seller-dashboard', [App\Http\Controllers\CategoryController::class, 'sellerdashboard']);
Route::get('/delete-account', [App\Http\Controllers\SellerloginController::class, 'delete_account']);
Route::post('/delete_acc', [App\Http\Controllers\SellerloginController::class, 'delete_acc']);

Route::get('/update-account/{any}', [App\Http\Controllers\SellerloginController::class, 'update_account']);
Route::post('/update_details/{any}', [App\Http\Controllers\SellerloginController::class, 'update_details']);

Route::get('/lock_location/{any}', [App\Http\Controllers\SellerloginController::class, 'lock_location']);
Route::get('/unlock_location/{any}', [App\Http\Controllers\SellerloginController::class, 'unlock_location']);



Route::get('/seller/buyer-order/file/{any}', [App\Http\Controllers\BuyerloginController::class, 'vewfile']);


Route::get('/seller/accounting/accbid', [App\Http\Controllers\SellerloginController::class, 'accbid']);
Route::get('/seller/accounting/biddrecive', [App\Http\Controllers\SellerloginController::class, 'biddrecive']);
Route::get('/seller/accounting/list', [App\Http\Controllers\SellerloginController::class, 'accountinglist']);
Route::get('/seller/accounting/totalshare', [App\Http\Controllers\SellerloginController::class, 'totalshare']);
Route::get('/seller/enquiry/list', [App\Http\Controllers\SellerloginController::class, 'list']);
Route::get('/seller/enquiry/deactivelist', [App\Http\Controllers\SellerloginController::class, 'deactivelist']);
Route::get('/seller/enquiry/myenclist', [App\Http\Controllers\SellerloginController::class, 'myenclist']);
Route::post('/seller/update_lat_long', [App\Http\Controllers\SellerloginController::class, 'update_lat_long'])->name('update_lat_long');
Route::post('/openqotationpage', [App\Http\Controllers\SellerloginController::class, 'openqotationpage'])->name('openqotationpage');
Route::post('/bidding_price', [App\Http\Controllers\SellerloginController::class, 'bidding_price'])->name('bidding_price');
Route::get('/seller/enquiry/view/{any}', [App\Http\Controllers\SellerloginController::class, 'vewsell']);
Route::get('/seller/enquiry/file/{any}', [App\Http\Controllers\SellerloginController::class, 'vewfile']);
Route::get('/accepet/{id}/{data_id}', [App\Http\Controllers\SellerloginController::class, 'accepet'])->name('accepet');
Route::get('/viewwork/{id}', [App\Http\Controllers\SellerloginController::class, 'viewwork'])->name('viewwork');



Route::get('/buyer-order', [App\Http\Controllers\BuyerloginController::class, 'buyer_dashboard'])->name('buyer-dashboard');
Route::get('/buyer-order/mylist', [App\Http\Controllers\BuyerloginController::class, 'mylist'])->name('mylist');
Route::get('/buyer-order/acc-list', [App\Http\Controllers\BuyerloginController::class, 'acc_list'])->name('acc-list');
Route::get('/price-list/{data_id}', [App\Http\Controllers\BuyerloginController::class, 'price_list'])->name('price-list');
Route::get('/accepted-list/{data_id}', [App\Http\Controllers\BuyerloginController::class, 'accepted_list'])->name('accepted-list');
   





    Route::get('/seller/logout', function()
{
    session()->pull('seller');
    return redirect('seller-register');
});
});









// buyer ---------------------------------------
Route::get('buyer-register', [App\Http\Controllers\BuyerloginController::class, 'buyer_register'])->name('buyer-register');
Route::post('buyer-register', [App\Http\Controllers\BuyerloginController::class, 'buyer_create'])->name('buyer-register');
Route::get('buyer-login', [App\Http\Controllers\BuyerloginController::class, 'buyer_login'])->name('buyer-login');
Route::post('buyer-login', [App\Http\Controllers\BuyerloginController::class, 'authenticate'])->name('buyer-login');
Route::post('buyer-login-form', [App\Http\Controllers\BuyerloginController::class, 'authenticate2'])->name('buyer-login-form');


Route::group(['middleware' => 'buyer_auth'], function()
{ 
    Route::get('/buyer-dashboard', [App\Http\Controllers\BuyerloginController::class, 'buyer_dashboard'])->name('buyer-dashboard');
    // Route::get('/price-list/{data_id}', [App\Http\Controllers\BuyerloginController::class, 'price_list'])->name('price-list');
   

    Route::get('/buyer/logout', function()
    {
        session()->pull('buyer');
        return redirect('buyer-register');
    });
});







// Admin---------------------------------------
Route::get('admin', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\LoginController::class, 'authenticate'])->name('login');

Route::group(['middleware' => 'admin_auth'], function()
{ 

Route::get('/dashboard', function () {
    return view('admin/dashboard');
});

// blog
Route::get('/admin/blog/add', [App\Http\Controllers\BlogController::class, 'index']);
Route::get('/admin/blog/list', [App\Http\Controllers\BlogController::class, 'view']);
Route::post('/admin/blog/create', [App\Http\Controllers\BlogController::class, 'create'])->name('admin.blog.create');
Route::get('/admin/blog/active/{any}', [App\Http\Controllers\BlogController::class, 'active'])->name('admin.blog.active');
Route::get('/admin/blog/deactive/{any}', [App\Http\Controllers\BlogController::class, 'deactive'])->name('admin.blog.deactive');
Route::get('/admin/blog/edit/{any}', [App\Http\Controllers\BlogController::class, 'edit'])->name('admin.blog.edit');
Route::post('/admin/blog/update/{any}', [App\Http\Controllers\BlogController::class, 'update'])->name('admin.blog.update');
Route::get('/admin/blog/delete/{any}', [App\Http\Controllers\BlogController::class, 'delete'])->name('admin.blog.delete');

// categoory
Route::get('/admin/category/add', [App\Http\Controllers\CategoryController::class, 'index']);
Route::get('/admin/category/list', [App\Http\Controllers\CategoryController::class, 'view']);
Route::post('/admin/category/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('admin.category.create');
Route::get('/admin/category/active/{any}', [App\Http\Controllers\CategoryController::class, 'active'])->name('admin.category.active');
Route::get('/admin/category/deactive/{any}', [App\Http\Controllers\CategoryController::class, 'deactive'])->name('admin.category.deactive');
Route::get('/admin/category/edit/{any}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('admin.category.edit');
Route::post('/admin/category/update/{any}', [App\Http\Controllers\CategoryController::class, 'update'])->name('admin.category.update');
Route::get('/admin/category/delete/{any}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('admin.category.delete');

// youtube
Route::get('/admin/yt/add', [App\Http\Controllers\YtController::class, 'index']);
Route::get('/admin/yt/list', [App\Http\Controllers\YtController::class, 'view']);
Route::post('/admin/yt/create', [App\Http\Controllers\YtController::class, 'create'])->name('admin.yt.create');
Route::get('/admin/yt/active/{any}', [App\Http\Controllers\YtController::class, 'active'])->name('admin.yt.active');
Route::get('/admin/yt/deactive/{any}', [App\Http\Controllers\YtController::class, 'deactive'])->name('admin.yt.deactive');
Route::get('/admin/yt/edit/{any}', [App\Http\Controllers\YtController::class, 'edit'])->name('admin.yt.edit');
Route::post('/admin/yt/update/{any}', [App\Http\Controllers\YtController::class, 'update'])->name('admin.yt.update');
Route::get('/admin/yt/delete/{any}', [App\Http\Controllers\YtController::class, 'delete'])->name('admin.yt.delete');


// Sub
Route::get('/admin/sub/add', [App\Http\Controllers\SubController::class, 'index']);
Route::get('/admin/sub/list', [App\Http\Controllers\SubController::class, 'view']);
Route::post('/admin/sub/create', [App\Http\Controllers\SubController::class, 'create'])->name('admin.sub.create');
Route::get('/admin/sub/active/{any}', [App\Http\Controllers\SubController::class, 'active'])->name('admin.sub.active');
Route::get('/admin/sub/deactive/{any}', [App\Http\Controllers\SubController::class, 'deactive'])->name('admin.sub.deactive');
Route::get('/admin/sub/edit/{any}', [App\Http\Controllers\SubController::class, 'edit'])->name('admin.sub.edit');
Route::post('/admin/sub/update/{any}', [App\Http\Controllers\SubController::class, 'update'])->name('admin.sub.update');
Route::get('/admin/sub/delete/{any}', [App\Http\Controllers\SubController::class, 'delete'])->name('admin.sub.delete');

// advertisement
Route::get('/admin/advertisement/add', [App\Http\Controllers\AdvertisementController::class, 'index']);
Route::get('/admin/advertisement/list', [App\Http\Controllers\AdvertisementController::class, 'view']);
Route::post('/admin/advertisement/create', [App\Http\Controllers\AdvertisementController::class, 'create'])->name('admin.advertisement.create');
Route::get('/admin/advertisement/active/{any}', [App\Http\Controllers\AdvertisementController::class, 'active'])->name('admin.advertisement.active');
Route::get('/admin/advertisement/deactive/{any}', [App\Http\Controllers\AdvertisementController::class, 'deactive'])->name('admin.advertisement.deactive');
Route::get('/admin/advertisement/edit/{any}', [App\Http\Controllers\AdvertisementController::class, 'edit'])->name('admin.advertisement.edit');
Route::post('/admin/advertisement/update/{any}', [App\Http\Controllers\AdvertisementController::class, 'update'])->name('admin.advertisement.update');
Route::get('/admin/advertisement/delete/{any}', [App\Http\Controllers\AdvertisementController::class, 'delete'])->name('admin.advertisement.delete');

// super
Route::get('/admin/super/add', [App\Http\Controllers\SuperController::class, 'index']);
Route::get('/admin/super/list', [App\Http\Controllers\SuperController::class, 'view']);
Route::post('/admin/super/create', [App\Http\Controllers\SuperController::class, 'create'])->name('admin.super.create');
Route::get('/admin/super/active/{any}', [App\Http\Controllers\SuperController::class, 'active'])->name('admin.super.active');
Route::get('/admin/super/deactive/{any}', [App\Http\Controllers\SuperController::class, 'deactive'])->name('admin.super.deactive');
Route::get('/admin/super/edit/{any}', [App\Http\Controllers\SuperController::class, 'edit'])->name('admin.super.edit');
Route::post('/admin/super/update/{any}', [App\Http\Controllers\SuperController::class, 'update'])->name('admin.super.update');
Route::get('/admin/super/delete/{any}', [App\Http\Controllers\SuperController::class, 'delete'])->name('admin.super.delete');



// product
Route::get('/admin/product/add', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/admin/product/list', [App\Http\Controllers\ProductController::class, 'view']);
Route::post('/admin/product/create', [App\Http\Controllers\ProductController::class, 'create'])->name('admin.product.create');
Route::get('/admin/product/active/{any}', [App\Http\Controllers\ProductController::class, 'active'])->name('admin.product.active');
Route::get('/admin/product/deactive/{any}', [App\Http\Controllers\ProductController::class, 'deactive'])->name('admin.product.deactive');
Route::get('/admin/product/edit/{any}', [App\Http\Controllers\ProductController::class, 'edit'])->name('admin.product.edit');
Route::post('/admin/product/update/{any}', [App\Http\Controllers\ProductController::class, 'update'])->name('admin.product.update');
Route::get('/admin/product/delete/{any}', [App\Http\Controllers\ProductController::class, 'delete'])->name('admin.product.delete');

// Wizz form
Route::get('/admin/wizz/add', [App\Http\Controllers\WizzController::class, 'index']);
Route::get('/admin/wizz/list', [App\Http\Controllers\WizzController::class, 'view']);
Route::post('/admin/wizz/create', [App\Http\Controllers\WizzController::class, 'create'])->name('admin.wizz.create');
Route::get('/admin/wizz/active/{any}', [App\Http\Controllers\WizzController::class, 'active'])->name('admin.wizz.active');
Route::get('/admin/wizz/deactive/{any}', [App\Http\Controllers\WizzController::class, 'deactive'])->name('admin.wizz.deactive');
Route::get('/admin/wizz/two/{id}', [App\Http\Controllers\WizzController::class, 'two'])->name('admin.wizz.two');
Route::get('/admin/wizz/three/{id}', [App\Http\Controllers\WizzController::class, 'three'])->name('admin.wizz.three');
Route::get('/admin/wizz/edit/{any}', [App\Http\Controllers\WizzController::class, 'edit'])->name('admin.wizz.edit');
Route::post('/admin/wizz/update/{any}', [App\Http\Controllers\WizzController::class, 'update'])->name('admin.wizz.update');
Route::post('/admin/wizz/updatethree/{any}', [App\Http\Controllers\WizzController::class, 'updatethree'])->name('admin.wizz.updatethree');
Route::get('/admin/wizz/delete/{any}', [App\Http\Controllers\WizzController::class, 'delete'])->name('admin.wizz.delete');


// testimonial
Route::get('/admin/testimonial/add', [App\Http\Controllers\TestimonialController::class, 'index']);
Route::get('/admin/testimonial/list', [App\Http\Controllers\TestimonialController::class, 'view']);
Route::post('/admin/testimonial/create', [App\Http\Controllers\TestimonialController::class, 'create'])->name('admin.testimonial.create');
Route::get('/admin/testimonial/active/{any}', [App\Http\Controllers\TestimonialController::class, 'active'])->name('admin.testimonial.active');
Route::get('/admin/testimonial/deactive/{any}', [App\Http\Controllers\TestimonialController::class, 'deactive'])->name('admin.testimonial.deactive');
Route::get('/admin/testimonial/edit/{any}', [App\Http\Controllers\TestimonialController::class, 'edit'])->name('admin.testimonial.edit');
Route::post('/admin/testimonial/update/{any}', [App\Http\Controllers\TestimonialController::class, 'update'])->name('admin.testimonial.update');
Route::get('/admin/testimonial/delete/{any}', [App\Http\Controllers\TestimonialController::class, 'delete'])->name('admin.testimonial.delete');







Route::get('/get_sub_category', [App\Http\Controllers\ProductController::class, 'getSubCategories'])->name('get_sub_category');
Route::get('/get_sup_category', [App\Http\Controllers\ProductController::class, 'getSupCategories'])->name('get_sup_category');

Route::get('/admin/userfinal/active/{any}', [App\Http\Controllers\CategoryController::class, 'activeuser'])->name('admin.userfinal.active');
Route::get('/admin/userfinal/deactive/{any}', [App\Http\Controllers\CategoryController::class, 'deactiveuser'])->name('admin.userfinal.deactive');
Route::get('/admin/joinmember/{any}', [App\Http\Controllers\CategoryController::class, 'joinmember'])->name('admin.joinmember');

Route::get('/admin/register/userlist', [App\Http\Controllers\CategoryController::class, 'userlist']);
Route::get('/admin/buyer/active/{any}', [App\Http\Controllers\CategoryController::class, 'buyeractive'])->name('admin.buyer.active');
Route::get('/admin/buyer/deactive/{any}', [App\Http\Controllers\CategoryController::class, 'buyerdeactive'])->name('admin.buyer.deactive');


Route::get('/admin/register/buyerlist', [App\Http\Controllers\CategoryController::class, 'buyerlist']);
Route::get('/admin/register/contractorlist', [App\Http\Controllers\CategoryController::class, 'contractorlist']);
Route::get('/admin/register/sellerlist', [App\Http\Controllers\CategoryController::class, 'sellerlist']);
Route::get('/admin/seller/active/{any}', [App\Http\Controllers\CategoryController::class, 'selleractive'])->name('admin.seller.active');
Route::get('/admin/seller/deactive/{any}', [App\Http\Controllers\CategoryController::class, 'sellerdeactive'])->name('admin.seller.deactive');

// Unit
Route::get('/admin/unit/add', [App\Http\Controllers\UnitController::class, 'index']);
Route::get('/admin/unit/list', [App\Http\Controllers\UnitController::class, 'view']);
Route::post('/admin/unit/create', [App\Http\Controllers\UnitController::class, 'create'])->name('admin.unit.create');
Route::get('/admin/unit/active/{any}', [App\Http\Controllers\UnitController::class, 'active'])->name('admin.unit.active');
Route::get('/admin/unit/deactive/{any}', [App\Http\Controllers\UnitController::class, 'deactive'])->name('admin.unit.deactive');
Route::get('/admin/unit/edit/{any}', [App\Http\Controllers\UnitController::class, 'edit'])->name('admin.unit.edit');
Route::post('/admin/unit/update/{any}', [App\Http\Controllers\UnitController::class, 'update'])->name('admin.unit.update');
Route::get('/admin/unit/delete/{any}', [App\Http\Controllers\UnitController::class, 'delete'])->name('admin.unit.delete');


Route::get('/admin/enquiry/list', [App\Http\Controllers\HomeController::class, 'quotationlist']);
Route::get('/admin/enquiry/view/{any}', [App\Http\Controllers\HomeController::class, 'vewsell']);
Route::get('/admin/enquiry/file/{any}', [App\Http\Controllers\HomeController::class, 'vewfile']);
Route::get('/admin/enquiry/deactivelist', [App\Http\Controllers\HomeController::class, 'deactivelist']);
Route::get('/admin/enquiry/biddlist', [App\Http\Controllers\HomeController::class, 'biddlist']);
Route::get('/admin/enquiry/acceptedbidding', [App\Http\Controllers\HomeController::class, 'acceptedbidding']);
Route::get('/admin/enquiry/totalbidding', [App\Http\Controllers\HomeController::class, 'totalbidding']);


Route::get('/accounting-ad/buyer-order', [App\Http\Controllers\BuyerloginController::class, 'buyer_dashboard_accounting']);
Route::get('/accounting-ad/price-list/{data_id}', [App\Http\Controllers\BuyerloginController::class, 'price_list_accounting']);
Route::get('/accounting-ad/accepted-list/{data_id}', [App\Http\Controllers\BuyerloginController::class, 'accepted_list_accounting']);



Route::get('/admin/logout', function()
{
    session()->pull('admin');
    return redirect('admin');
});

});


Route::post('confirm', [\App\Http\Controllers\SellerloginController::class, 'confirmPayment'])->name('confirm');



// Guru Maurya Work
Route::prefix('super-admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('super-admin.dashboard');
    Route::get('web-settings', [WebSettingsController::class, 'edit'])->name('super-admin.web-settings.edit');
    Route::post('web-settings/save', [WebSettingsController::class, 'save'])->name('super-admin.web-settings.save');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('super-admin.categories.index');
    Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('super-admin.categories.create');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('super-admin.categories.store');
    Route::get('categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('super-admin.categories.edit');
    Route::put('categories/{id}', [AdminCategoryController::class, 'update'])->name('super-admin.categories.update');
    Route::delete('categories/{id}', [AdminCategoryController::class, 'destroy'])->name('super-admin.categories.destroy');
});


