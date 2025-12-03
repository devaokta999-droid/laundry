<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// dY- Import semua controller yang digunakan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\StatusController;
use App\Models\Order;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| dYO? PUBLIC AREA (Akses untuk semua pengguna)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/promos', [HomeController::class, 'promos'])->name('promos');

// dY' Semua orang (termasuk tamu) bisa melihat daftar layanan
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// dY� Pemesanan laundry �?" pelanggan bisa pesan tanpa login
Route::get('/order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/status-laundry', [StatusController::class, 'customer'])->name('status.index');

/*
|--------------------------------------------------------------------------
| dY"? AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
// Halaman registrasi publik dinonaktifkan (admin menambah user via menu Role & Permission)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');

/*
|--------------------------------------------------------------------------
| dY` CUSTOMER AREA (User login biasa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::resource('/layanan', LayananController::class);
});

/*
|--------------------------------------------------------------------------
| dY_ ADMIN / KASIR / DEVA AREA
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
        | dY'� Kelola Layanan Laundry
        |-------------------------------
        */
        Route::get('services', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            return app(ServiceController::class)->index();
        })->name('services.index');

        Route::get('services/create', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(ServiceController::class)->create();
        })->name('services.create');

        Route::post('services', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(ServiceController::class)->store($request);
        })->name('services.store');

        Route::get('services/{id}/edit', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(ServiceController::class)->edit($id);
        })->name('services.edit');

        Route::put('services/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(ServiceController::class)->update($request, $id);
        })->name('services.update');

        Route::delete('services/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(ServiceController::class)->destroy($id);
        })->name('services.destroy');

        /*
        |-------------------------------
        | Kelola Role & Permission (Admin saja)
        |-------------------------------
        */
        Route::resource('roles', RoleController::class)->except(['show']);

        /*
        |-------------------------------
        | Kelola Tim Profesional (Admin saja)
        |-------------------------------
        */
        Route::resource('team', TeamMemberController::class)->except(['show']);
        Route::get('profile', [AdminProfileController::class, 'show'])->name('profile');
        Route::post('profile', [AdminProfileController::class, 'update'])->name('profile.update');

        /*
        |-------------------------------
        | dY_ Nota Digital Laundry Satuan
        |-------------------------------
        */
        Route::get('nota', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            return app(NotaController::class)->index($request);
        })->name('nota.index');

        Route::post('nota/store', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            return app(NotaController::class)->store($request);
        })->name('nota.store');

        // Hapus banyak nota sekaligus
        Route::delete('nota/bulk-destroy', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(NotaController::class)->bulkDestroy($request);
        })->name('nota.bulk_destroy');

        // dY-"�,? Cetak & Unduh PDF Nota
        Route::get('nota/{id}/print', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            return app(NotaController::class)->print($id);
        })->name('nota.print');

        // dY"? Detail Nota
        Route::get('nota/{id}', function (Request $request, $id) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            $nota = \App\Models\Nota::with('items.item')->findOrFail($id);
            return view('admin.nota.show', compact('nota'));
        })->name('nota.show');

        // dY-"�,? Print Direct
        Route::get('nota/{nota}/print-direct', [NotaController::class, 'printToPrinter'])
            ->name('nota.print_direct');

        // dY'� Tandai Lunas
        Route::post('nota/{nota}/lunas', [NotaController::class, 'markLunas'])
            ->name('nota.lunas');

        // dY'3 Proses Pembayaran (cash/transfer, partial/full)
        Route::post('nota/{nota}/pay', [NotaController::class, 'pay'])
            ->name('nota.pay');

        // dY-`�,? Hapus Nota
        Route::delete('nota/{id}', [NotaController::class, 'destroy'])
            ->name('nota.destroy');

        // dY"S Laporan Keuangan
        Route::get('laporan', [NotaController::class, 'laporan'])
            ->name('laporan');

        // dY" Export Laporan ke Excel
        Route::get('laporan/export-excel', [NotaController::class, 'exportExcel'])
            ->name('laporan.exportExcel');

        /*
        |------------------------------------------------------------------
        | Status Laundry (Admin & Kasir)
        |------------------------------------------------------------------
        */
        Route::get('orders/status', function (Request $request) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin', 'kasir');
            return app(StatusController::class)->admin($request);
        })->name('orders.status');
        Route::get('orders/{order}/create-nota', function (Request $request, Order $order) {
            app(RoleMiddleware::class)->handle($request, function () {}, 'admin');
            return app(StatusController::class)->createNota($request, $order);
        })->name('orders.status.create_nota');
    });

        /*
        |--------------------------------------------------------------------------
        | �o. Route Public Tambahan (Show Nota di luar admin)
        |--------------------------------------------------------------------------
        */
        Route::get('/nota/{id}/show', [NotaController::class, 'show'])->name('nota.show');
        // Edit Nota
        Route::get('/admin/nota/{id}/edit', [NotaController::class, 'edit'])->name('admin.nota.edit');
        // Update Nota
        Route::put('/admin/nota/{id}', [NotaController::class, 'update'])->name('admin.nota.update');
