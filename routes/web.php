<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Category as CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::get('/', [FrontEndController::class, 'index'])->name('FrontEnd');

Route::prefix('/shop')->name('shop')->group(function () {
    Route::get('/{category?}', [FrontEndController::class, 'shop'])->name('index');
    Route::match(['get', 'post'], '/filter-sort/{sort?}', [FrontEndController::class, 'filterSort'])->name('filter-sort');
});

Route::get('/single-product/{slug}', [FrontEndController::class, 'single'])->name('single');

Route::prefix('/cart')->group(function () {
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::get('/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::get('/', [CartController::class, 'showCart'])->name('cart');
    Route::post('/checkCoupon', [CartController::class, 'checkCoupon'])->name('checkCoupon');
});

Route::middleware('auth')->get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::middleware('auth')->post('/checkout-submit', [CartController::class, 'checkoutSubmit'])->name('checkoutSubmit');


// For Seller Admin Dashboard
Route::middleware('auth', 'admin')->get('/admin', [AdminController::class, 'index'])->name('adminDashboard');

// Group for Category, Product, and Coupons CRUD
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('/category', CategoryController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/coupons', CouponController::class);
});


Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified', 'admin');