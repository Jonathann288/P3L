<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/registerOrganisasi', function () {
    return view('registerOrganisasi');
})->name('registerOrganisasi');

Route::get('/loginOrganisasi', function () {
    return view('loginOrganisasi');
})->name('loginOrganisasi');

Route::get('/login-organisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/login-organisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');
Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logoutOrganisasi');

Route::get('/dashboard-organisasi', function () {
    $organisasi = session('organisasi');
    if (!$organisasi) return redirect()->route('loginOrganisasi');
    return view('organisasi.donasi', compact('organisasi'));
})->name('dashboardOrganisasi');

Route::get('/loginDashboard', [AuthController::class, 'showLoginForm'])->name('loginDashboard');
Route::post('/loginDashboard', [AuthController::class, 'loginPegawai'])->name('loginPegawai.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['checkjabatan:Admin'])->group(function () {
    Route::get('/Dashboard', function () {
        return view('admin.Dashboard');
    })->name('admin.Dashboard');
});
Route::middleware(['checkjabatan:Owner'])->group(function () {
    Route::get('/DashboardOwner', function () {
        return view('DashboardOwner');
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
