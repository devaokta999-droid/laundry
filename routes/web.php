<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// 🧭 Import semua controller yang digunakan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\NotaController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC AREA (Akses untuk semua pengguna)
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
| 👤 CUSTOMER AREA (User login biasa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('/layanan', LayananController::class);
});

/*
|--------------------------------------------------------------------------
| 🧾 ADMIN / KASIR / DEVA AREA
|--------------------------------------------------------------------------
| - Admin bisa melihat semua transaksi, kelola layanan, nota, kasir, dll.
| - Middleware role dijalankan manual via app(RoleMiddleware::class)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        /*
        |-------------------------------
        | 💼 Kelola Layanan Laundry
        |-------------------------------
        */
        Route::get('services', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(ServiceController::class)->index();
        })->name('services.index');

        Route::get('services/create', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(ServiceController::class)->create();
        })->name('services.create');

        Route::post('services', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(ServiceController::class)->store($request);
        })->name('services.store');

        Route::get('services/{id}/edit', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(ServiceController::class)->edit($id);
        })->name('services.edit');

        Route::put('services/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(ServiceController::class)->update($request, $id);
        })->name('services.update');

        Route::delete('services/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(ServiceController::class)->destroy($id);
        })->name('services.destroy');

        /*
        |-------------------------------
        | 💵 Kasir - Transaksi Penjualan
        |-------------------------------
        */
        Route::get('cashier', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(CashierController::class)->index();
        })->name('cashier.index');

        Route::post('cashier/store', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(CashierController::class)->store($request);
        })->name('cashier.store');

        Route::post('cashier/print', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(CashierController::class)->print($request);
        })->name('cashier.print');

        /*
        |-------------------------------
        | 📜 Riwayat Transaksi
        |-------------------------------
        */
        Route::get('transactions', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'deva');
            return app(TransactionController::class)->index();
        })->name('transactions.index');

        /*
        |-------------------------------
        | 🧾 Nota Digital Laundry Satuan
        |-------------------------------
        */
        Route::get('nota', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(NotaController::class)->index();
        })->name('nota.index');

        Route::post('nota/store', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(NotaController::class)->store($request);
        })->name('nota.store');

        // 🖨️ Cetak & Unduh PDF Nota
        Route::get('nota/{id}/print', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            return app(NotaController::class)->print($id);
        })->name('nota.print');

        // 🔍 Detail Nota
        Route::get('nota/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir', 'deva');
            $nota = \App\Models\Nota::with('items.item')->findOrFail($id);
            return view('admin.nota.show', compact('nota'));
        })->name('nota.show');

        // 🖨️ Print Direct
        Route::get('nota/{nota}/print-direct', [NotaController::class, 'printToPrinter'])
            ->name('nota.print_direct');

        // 💰 Tandai Lunas
        Route::post('nota/{nota}/lunas', [NotaController::class, 'markLunas'])
            ->name('nota.lunas');

        // 🗑️ Hapus Nota
        Route::delete('nota/{id}', [NotaController::class, 'destroy'])
            ->name('nota.destroy');

        // 📊 Laporan Keuangan
        Route::get('laporan', [NotaController::class, 'laporan'])
            ->name('laporan');

        // 📤 Export Laporan ke Excel
        Route::get('laporan/export-excel', [NotaController::class, 'exportExcel'])
            ->name('laporan.exportExcel');
    });

/*
|--------------------------------------------------------------------------
| ✅ Route Public Tambahan (Show Nota di luar admin)
|--------------------------------------------------------------------------
*/
Route::get('/nota/{id}/show', [NotaController::class, 'show'])->name('nota.show');

