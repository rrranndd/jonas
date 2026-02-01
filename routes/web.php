<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\PelangganApiController;
use App\Http\Controllers\Api\PaketApiController;
use App\Http\Controllers\Api\InvoiceApiController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('api')->group(function () {

    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::get('/orders/{kode}', [OrderApiController::class, 'show']);
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::put('/orders/{kode}', [OrderApiController::class, 'update']);
    Route::delete('/orders/{kode}', [OrderApiController::class, 'destroy']);

    Route::get('/pelanggan', [PelangganApiController::class, 'index']);
    Route::get('/pelanggan/{id}', [PelangganApiController::class, 'show']);

    Route::get('/paket', [PaketApiController::class, 'index']);
    Route::get('/paket/{id}', [PaketApiController::class, 'show']);
    Route::post('/paket', [PaketApiController::class, 'store']);
    Route::put('/paket/{id}', [PaketApiController::class, 'update']);
    Route::delete('/paket/{id}', [PaketApiController::class, 'destroy']);

    Route::get('/invoice', [InvoiceApiController::class, 'index']);
    Route::get('/invoice/{no}', [InvoiceApiController::class, 'show']);
    Route::post('/invoice', [InvoiceApiController::class, 'store']);
    Route::put('/invoice/{no}', [InvoiceApiController::class, 'updateStatus']);

});

Route::middleware(['adminauth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/search', [PelangganController::class, 'search'])->name('pelanggan.search');
    Route::get('/pelanggan/get-by-name', [PelangganController::class, 'getByName'])->name('pelanggan.byName');
    Route::get('/pelanggan/{id}/order', [PelangganController::class, 'riwayat'])->name('pelanggan.order');

    Route::get('/paket', [PaketController::class, 'index']);
    Route::post('/paket/store', [PaketController::class, 'store']);
    Route::post('/paket/update/{id}', [PaketController::class, 'update']);
    Route::get('/paket/delete/{id}', [PaketController::class, 'destroy']);
    Route::get('/paket/search', [PaketController::class, 'search'])->name('paket.search');


    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::post('/order/update/{kode}', [OrderController::class, 'update'])->name('order.update');
    Route::get('/order/delete/{kode}', [OrderController::class, 'destroy'])->name('order.delete');

    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::get('/invoice/order-detail/{kode}', [InvoiceController::class, 'orderDetail']);
    Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/show/{no}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::post('/invoice/lunasi/{no}', [InvoiceController::class, 'lunasi'])->name('invoice.lunasi');
    Route::get('/invoice/search', [InvoiceController::class, 'search'])->name('invoice.search');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cari', [LaporanController::class, 'filter'])->name('laporan.filter');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});
