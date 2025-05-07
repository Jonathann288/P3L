<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliControllrs;
use App\Http\Controllers\PenitipControllrs;
use App\Http\Controllers\organisasiControllrs;


Route::get('/', function () {
    return view('beranda');
})->name('beranda');

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

// Route::get('/user', function () {
//     return view('user');
// })->name('user');

Route::get('/penitip', function () {
    return view('penitip');
})->name('penitip');

Route::get('/donasi', function () {
    return view('donasi');
})->name('donasi');

Route::get('/requestBarang', function () {
    return view('requestBarang');
})->name('requestBarang');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Menampilkan form login Penitip
Route::get('login-penitip', [AuthController::class, 'showLoginFormPenitip'])->name('loginPenitip');

// Menangani form login (POST) Penitip
Route::post('login-penitip', [AuthController::class, 'loginPenitip'])->name('loginPenitip.post');

// Proteksi route dengan middleware 'penitip'
Route::middleware(['penitip'])->group(function () {
    // Halaman shop untuk Penitip
    Route::get('/Shop-Penitip', function () {
        return view('penitip.Shop-Penitip');
    })->name('penitip.Shop-Penitip');
});


Route::get('/registerPembeli', [PembeliControllrs::class, 'showRegisterForm'])->name('registerPembeliForm');
Route::post('/registerPembeli', [PembeliControllrs::class, 'registerPembeli'])->name('registerPembeli');

Route::get('/registerPenitip', [PenitipControllrs::class, 'showRegisterFormPenitip'])->name('registerPenitipForm');
Route::post('/registerPenitip', [PenitipControllrs::class, 'registerPenitip'])->name('registerPenitip.post');



// Menampilkan form login
Route::get('login-pembeli', [AuthController::class, 'showLoginForm'])->name('loginPembeli');
// Menangani form login (POST)
Route::post('login-pembeli', [AuthController::class, 'loginPembeli'])->name('loginPembeli.post');
Route::middleware(['pembeli'])->group(function () {
    // Halaman shop untuk pembeli
    Route::get('/Shop-Pembeli', function () {
        return view('pembeli.Shop-Pembeli');
    })->name('pembeli.Shop-Pembeli');
});

Route::get('/registerOrganisasi', [organisasiControllrs::class, 'showRegisterOrganisasi'])->name('registerOrganisasi');
// Route::post('/registerOrganisasi', [organisasiControllrs::class, 'registerOrganisasi'])->name('registerOrganisasi.post');
Route::get('/loginOrganisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/loginOrganisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');

Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logoutOrganisasi');

// Proses form register
Route::post('/registerOrganisasi', [organisasiControllrs::class, 'registerOrganisasi'])->name('registerOrganisasi.post');

Route::middleware(['organisasi'])->group(function () {
    Route::get('/donasi-organisasi', function () {
        return view('organisasi.donasi-organisasi');
    })->name('organisasi.DonasiOrganisasi');
});

Route::get('/loginDashboard', [AuthController::class, 'showLoginFormPegawai'])->name('loginDashboard');
Route::post('/loginDashboard', [AuthController::class, 'loginPegawai'])->name('loginPegawai.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['checkjabatan:Admin'])->group(function () {
    Route::get('/Dashboard', function () {
        return view('admin.Dashboard');
    })->name('admin.Dashboard');
});
Route::middleware(['checkjabatan:Owner'])->group(function () {
    Route::get('/DashboardOwner', function () {
        return view('owner.DashboardOwner');
    })->name('owner.DashboardOwner');
});
Route::middleware(['checkjabatan:Customer Service'])->group(function () {
    Route::get('/DashboardCS', function () {
        return view('customerservice.DashboardCS');
    })->name('CustomerService.DashboardCS');
});
Route::middleware(['checkjabatan:Gudang'])->group(function () {
    Route::get('/DashboardGudang', function () {
        return view('gudang.DashboardGudang');
    })->name('gudang.DashboardGudang');
});
Route::middleware(['checkjabatan:Kurir'])->group(function () {
    Route::get('/DashboardKurir', function () {
        return view('kurir.DashboardKurir');
    })->name('kurir.DashboardKurir');
});
Route::middleware(['checkjabatan:Hunter'])->group(function () {
    Route::get('/DashboardHunter', function () {
        return view('hunter.DashboardHunter');
    })->name('hunter.DashboardHunter');
});
