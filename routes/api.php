<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllerAPI;
use App\Http\Controllers\BarangControllersApi;
use App\Http\Controllers\KomisiControllersAPI;
use App\Http\Controllers\MerchendiseControllersAPI;
use App\Http\Controllers\TransaksiPenitipanControllersAPI;
use App\Http\Controllers\TransaksiPenjualanControllersAPI;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json([
        'message' => 'API is working!',
        'timestamp' => now()
    ]);
});
Route::get('/shop-show', [BarangControllersApi::class, 'apiShowShop']);
Route::get('/barang/{id_barang}', [BarangControllersApi::class, 'showDetailApi']);
// Authentication Routes (Public - No middleware)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthControllerAPI::class, 'login']);
});

// Protected Routes (Require authentication)
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('/logout', [AuthControllerAPI::class, 'logout']);
    Route::post('/logout-all', [AuthControllerAPI::class, 'logoutAll']);
    Route::get('/me', [AuthControllerAPI::class, 'me']);
    Route::get('/komisi/history', [KomisiControllersAPI::class, 'getKomisiHistory']);
    Route::get('/merchandise', [MerchendiseControllersAPI::class, 'index']);
    Route::post('/claim-merchandise', [MerchendiseControllersAPI::class, 'claimMerchandise']);
    Route::get('/pembeli/history', [App\Http\Controllers\PembeliControllersAPI::class, 'getHistoryTransaksi']);
     //PENITIP
     Route::get('/penitip/history', [TransaksiPenitipanControllersAPI::class, 'getHistoryForPenitip']);
 // <-- TAMBAHKAN RUTE DI BAWAH INI -->
    // TOP SELLER
    Route::get('/topseller', [App\Http\Controllers\TopSellerControllerAPI::class, 'getTopSellers']);

    Route::get('/transaksipenjualan', [TransaksiPenjualanControllersAPI::class, 'index']);

    // Endpoint khusus untuk ambil transaksi dengan metode pengantaran "diantar_kurir"
    Route::get('/transaksipenjualan/diantar-kurir', [TransaksiPenjualanControllersAPI::class, 'getDiantarKurir']);

    // Endpoint untuk ambil detail transaksi berdasarkan ID
    Route::get('/transaksipenjualan/{id}', [TransaksiPenjualanControllersAPI::class, 'show']);

    // Endpoint untuk ambil transaksi berdasarkan status pengiriman (pending, dikirim, selesai, dll.)
    Route::get('/transaksipenjualan/status/{status}', [TransaksiPenjualanControllersAPI::class, 'getByStatusPengiriman']);

    // Endpoint untuk ambil ringkasan statistik transaksi
    Route::get('/transaksipenjualan-summary', [TransaksiPenjualanControllersAPI::class, 'getSummary']);

    Route::put('/transaksipenjualan/{id}/update-status', [TransaksiPenjualanControllersAPI::class, 'updateStatusTransaksi']);
});