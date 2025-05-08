<?php

use App\Http\Controllers\PembeliControllrs;
use App\Http\Controllers\PenitipControllrs;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisasiControllrs;
use App\Http\Controllers\lupaPasswordOrganisasiControllers;
use App\Http\Controllers\lupaPasswordPembeliControllers;

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

Route::get('/loginPenitip', function () {
    return view('loginPenitip');
})->name('loginPenitip');

Route::get('/resetPasswordOrganisasi', function () {
    return view('LupaPasswordOrg.resetPasswordOrganisasi');
})->name('LupaPasswordOrg.resetPasswordOrganisasi');

Route::get('/resetPasswordPembeli', function () {
    return view('LupaPasswordPembeli.resetPasswordPembeli');
})->name('LupaPasswordPembeli.resetPasswordPembeli');



Route::get('/lupaPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'showLinkForm'])->name('LupaPasswordOrg.lupaPasswordOrganisasi');
Route::post('/lupaPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'lupaPasswordOrganisasiPost'])->name('LupaPasswordOrg.lupaPasswordOrganisasi.post');
Route::get('/pesanLupaPasswordOrganisasi/{token}', [lupaPasswordOrganisasiControllers::class, 'showLinkForm'])->name('password.forgot.link');

Route::get('/lupaPasswordPembeli', [lupaPasswordPembeliControllers::class, 'showLinkFormPembeli'])->name('LupaPasswordPembeli.lupaPasswordPembeli');
Route::post('/lupaPasswordPembeli', [lupaPasswordPembeliControllers::class, 'lupaPasswordPembeliPost'])->name('LupaPasswordPembeli.lupaPasswordPembeli.post');
Route::get('/pesanLupaPasswordPembeli/{token}', [lupaPasswordPembeliControllers::class, 'showLinkFormPembeli'])->name('password.forgot.link');



Route::get('/registerPembeli', [PembeliControllrs::class, 'showRegisterForm'])->name('registerPembeliForm');
Route::post('/registerPembeli', [PembeliControllrs::class, 'registerPembeli'])->name('registerPembeli');

Route::get('/registerPenitip', [PenitipControllrs::class, 'showRegisterFormPenitip'])->name('registerPenitipForm');
Route::post('/registerPenitip', [PenitipControllrs::class, 'registerPenitip'])->name('registerPenitip.post');

// Menampilkan form login
Route::get('login-pembeli', [AuthController::class, 'showLoginForm'])->name('loginPembeli');
// Menangani form login (POST)
Route::post('login-pembeli', [AuthController::class, 'loginPembeli'])->name('loginPembeli.post');


Route::get('/registerOrganisasi', [OrganisasiControllrs::class, 'showRegisterOrganisasi'])->name('registerOrganisasi');
Route::post('/registerOrganisasi', [OrganisasiControllrs::class, 'registerOrganisasi'])->name('registerOrganisasi.post');
Route::get('/loginOrganisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/loginOrganisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');

// Route::get('/organisasi', [OrganisasiControllrs::class, 'index'])->name('organisasi.index');
// Route::get('/organisasi/create', [OrganisasiControllrs::class, 'create'])->name('organisasi.create');
// Route::post('/organisasi', [OrganisasiControllrs::class, 'store'])->name('organisasi.store');
// Route::get('/organisasi/{id}', [OrganisasiControllrs::class, 'show'])->name('organisasi.show');
// Route::get('/organisasi/{id}/edit', [OrganisasiControllrs::class, 'edit'])->name('organisasi.edit');
// Route::put('/organisasi/{id}', [OrganisasiControllrs::class, 'update'])->name('organisasi.update');
// Route::delete('/organisasi/{id}', [OrganisasiControllrs::class, 'destroy'])->name('organisasi.destroy');
// Route::get('/organisasi-search', [OrganisasiControllrs::class, 'search'])->name('organisasi.search');

Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logoutOrganisasi');


Route::get('/dashboard-organisasi', function () {
    $organisasi = session('organisasi');
    if (!$organisasi)
        return redirect()->route('loginOrganisasi');
    return view('donasi', compact('organisasi'));
})->name('dashboardOrganisasi');