<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\QuoteController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\AuthController;

/*
|--------------------------------------------------------------------------
| B2B Wholesale Frontend Routes
|--------------------------------------------------------------------------
|
| All frontend web routes are registered here and loaded by bootstrap/app.php.
|
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])
    ->name('frontend.home');

// Products Catalog & Details
Route::get('/products', [ProductController::class, 'index'])
    ->name('frontend.products.index');

Route::get('/products/{id}', [ProductController::class, 'show'])
    ->name('frontend.products.show');

// Categories & Brands
Route::get('/categories', [ProductController::class, 'categories'])
    ->name('frontend.categories.index');

Route::get('/categories/{slug}', [ProductController::class, 'categoryShow'])
    ->name('frontend.categories.show');

Route::get('/brands/{slug}', [ProductController::class, 'brandShow'])
    ->name('frontend.brands.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])
    ->name('frontend.cart.index');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('frontend.cart.add');

Route::post('/cart/update', [CartController::class, 'update'])
    ->name('frontend.cart.update');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])
    ->name('frontend.cart.remove');

Route::post('/cart/clear', [CartController::class, 'clear'])
    ->name('frontend.cart.clear');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('frontend.checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('frontend.checkout.store');

// Orders Routes
Route::get('/orders', [OrderController::class, 'index'])
    ->name('frontend.orders.index');

Route::get('/orders/{id}', [OrderController::class, 'show'])
    ->name('frontend.orders.show');

// Quote Request Routes
Route::get('/quotes/create', [QuoteController::class, 'create'])
    ->name('frontend.quotes.create');

Route::post('/quotes', [QuoteController::class, 'store'])
    ->name('frontend.quotes.store');

// Account Dashboard & Profile
Route::get('/account', [AccountController::class, 'index'])
    ->name('frontend.account');

Route::get('/account/profile', [AccountController::class, 'profile'])
    ->name('frontend.account.profile');

Route::post('/account/profile', [AccountController::class, 'updateProfile'])
    ->name('frontend.account.profile.update');

Route::get('/account/wishlist', [AccountController::class, 'wishlist'])
    ->name('frontend.account.wishlist');

Route::post('/wishlist/add', [AccountController::class, 'addWishlist'])
    ->name('frontend.wishlist.add');

Route::post('/wishlist/remove/{id}', [AccountController::class, 'removeWishlist'])
    ->name('frontend.wishlist.remove');

// Authentication Routes
Route::get('/login', [AuthController::class, 'login'])
    ->name('frontend.login');

Route::post('/login', [AuthController::class, 'storeLogin'])
    ->name('frontend.login.store');

Route::get('/register', [AuthController::class, 'register'])
    ->name('frontend.register');

Route::post('/register', [AuthController::class, 'storeRegister'])
    ->name('frontend.register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('frontend.logout');

// Pages
Route::get('/about', function () {
    return view('frontend.about');
})->name('frontend.about');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('frontend.contact');
