<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\ManajemenBarangController;
use App\Http\Controllers\HistoryBarangController;
use App\Http\Controllers\DataStokController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\LaporanStokController;


/*
|--------------------------------------------------------------------------
| Redirect Awal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Barang Masuk
    |--------------------------------------------------------------------------
    */

    Route::get('/barang-masuk', [BarangMasukController::class, 'index'])
    ->name('barang-masuk.index');

    Route::post('/barang-masuk', [BarangMasukController::class, 'store'])
    ->name('barang-masuk.store');

    Route::put('/barang-masuk/{barangMasuk}', [BarangMasukController::class, 'update'])
    ->name('barang-masuk.update');

    Route::delete('/barang-masuk/{barangMasuk}', [BarangMasukController::class, 'destroy'])
    ->name('barang-masuk.destroy');

    Route::get('/barang-masuk/show/{nomor_faktur}', [BarangMasukController::class, 'show'])
    ->name('barang-masuk.show');

    /*
    |--------------------------------------------------------------------------
    | Barang Keluar
    |--------------------------------------------------------------------------
    */

    Route::get('/barang-keluar', [BarangKeluarController::class, 'index'])
        ->name('barang-keluar.index');

    Route::post('/barang-keluar', [BarangKeluarController::class, 'store'])
        ->name('barang-keluar.store');

    Route::put('/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'update'])
        ->name('barang-keluar.update');

    Route::delete('/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'destroy'])
        ->name('barang-keluar.destroy');
    /*
    |--------------------------------------------------------------------------
    | Manajemen Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/manajemen-barang', [ManajemenBarangController::class, 'index'])
        ->name('manajemen-barang');

    Route::post('/manajemen-barang', [ManajemenBarangController::class, 'store'])
        ->name('manajemen-barang.store');

    Route::put('/manajemen-barang/{id}', [ManajemenBarangController::class, 'update'])
        ->name('manajemen-barang.update');

    Route::delete('/manajemen-barang/{id}', [ManajemenBarangController::class, 'destroy'])
        ->name('manajemen-barang.destroy');

    /*
    |--------------------------------------------------------------------------
    | Data Stok (sementara)
    |--------------------------------------------------------------------------
    */

   Route::get(
    '/data-stock',
    [DataStokController::class, 'index']
)->name('data-stok.index');;

    /*
    |--------------------------------------------------------------------------
    | Laporan Stok (sementara)
    |--------------------------------------------------------------------------
    */

    Route::get(
    '/laporan-stok',
    [LaporanStokController::class, 'index']
)->name('laporan-stok.index');

    Route::get(
        '/laporan-stok/export-pdf',
        [LaporanStokController::class, 'exportPdf']
)->name('laporan-stok.export-pdf');

    /*
    |--------------------------------------------------------------------------
    | Manajemen User (sementara)
    |--------------------------------------------------------------------------
    */

    Route::get('/manajemen-user', [ManajemenUserController::class, 'index'])
    ->name('manajemen-user');

    Route::post('/manajemen-user/store', [ManajemenUserController::class, 'store'])
        ->name('manajemen-user.store');

    Route::put('/manajemen-user/{user}', [ManajemenUserController::class, 'update'])
        ->name('manajemen-user.update');

    Route::delete('/manajemen-user/{user}', [ManajemenUserController::class, 'destroy'])
        ->name('manajemen-user.destroy');

    /*
    |--------------------------------------------------------------------------
    | History Barang (sementara)
    |--------------------------------------------------------------------------
    */

    Route::get(
    '/history-barang/{unit}',
    [HistoryBarangController::class, 'index']
    )->name('history-barang');

});