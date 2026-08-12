<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\FormComponentsController;

use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\CompaniesController;
use App\Http\Controllers\Admin\CompanyDetailsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are automatically loaded under the 'admin' prefix and
| 'admin.' name prefix via bootstrap/app.php.
|
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('orders/registered', [OrderController::class, 'registeredOrders'])->name('orders.registered');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', OrderController::class);
    Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
    Route::get('customers-register', [UserController::class, 'customersRegister'])->name('customers.register');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomersController::class);
    Route::resource('companies', CompaniesController::class);
    
    // Platform Company Details Routes
    Route::get('company-details', [CompanyDetailsController::class, 'index'])->name('company-details.index');
    Route::get('company-details/edit', [CompanyDetailsController::class, 'edit'])->name('company-details.edit');
    Route::put('company-details', [CompanyDetailsController::class, 'update'])->name('company-details.update');

    // Warehouse & Stock Management Routes
    Route::resource('warehouses', WarehouseController::class);
    Route::get('stocks/in', [StockController::class, 'stockIn'])->name('stocks.in');
    Route::post('stocks/in', [StockController::class, 'processStockIn'])->name('stocks.process-in');
    Route::get('stocks/out', [StockController::class, 'stockOut'])->name('stocks.out');
    Route::post('stocks/out', [StockController::class, 'processStockOut'])->name('stocks.process-out');
    Route::post('stocks/{stock}/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::resource('stocks', StockController::class);

    Route::get('/forms-demo', [FormComponentsController::class, 'index'])->name('forms.demo');
    Route::post('/forms-demo', [FormComponentsController::class, 'testSubmit'])->name('forms.demo.submit');

    Route::post('/clear-cache', [CacheController::class, 'clear'])->name('clear-cache');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});



