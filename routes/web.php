<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LayananController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC AREA
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/promos', [HomeController::class, 'promos'])->name('promos');

// 💧 Semua orang (termasuk tamu) bisa melihat daftar layanan
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// 🧺 Pemesanan laundry — pelanggan bisa pesan tanpa login
Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

/*
|--------------------------------------------------------------------------
| 🔐 AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 👤 CUSTOMER AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('/layanan', LayananController::class);
});

/*
|--------------------------------------------------------------------------
| 🧾 ADMIN / KASIR / DEVA AREA (Tanpa Kernel)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        // 🔹 Gunakan closure di dalam group, bukan di middleware array
        Route::group([], function () {

            // 🧰 Kelola Layanan
            Route::get('services', function (\Illuminate\Http\Request $request) {
                app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
                return app(ServiceController::class)->index();
            })->name('services.index');

            Route::resource('services', ServiceController::class)->except(['show']);

            // 📜 Transaksi
            Route::get('transactions', function (\Illuminate\Http\Request $request) {
                app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
                return app(TransactionController::class)->index();
            })->name('transactions.index');

            // 💵 Kasir
            Route::get('cashier', function (\Illuminate\Http\Request $request) {
                app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
                return app(CashierController::class)->index();
            })->name('cashier.index');

            Route::post('cashier/print', function (\Illuminate\Http\Request $request) {
                app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
                return app(CashierController::class)->print($request);
            })->name('cashier.print');
        });
    });
