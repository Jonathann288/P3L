<?php

use App\Http\Controllers\JabatanControllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembeliControllrs;
use App\Http\Controllers\PenitipControllrs;
use App\Http\Controllers\PegawaiControllers;
use App\Http\Controllers\AlamatControllers;
use App\Http\Controllers\OrganisasiControllrs;
use App\Http\Controllers\KategoriBarangControllers;
use App\Http\Controllers\BarangControllers;
use App\Http\Controllers\lupaPasswordOrganisasiControllers;
use App\Http\Controllers\lupaPasswordPembeliControllers;
use App\Http\Controllers\OwnerControllers;


Route::get('/', function () {
    return view('beranda');
})->name('beranda');
Route::get('/shop/barang/', function () {
    return view('shop.detail_barang');
})->name('shop.detail_barang');

Route::get('/shop', [BarangControllers::class, 'showShop'])->name('shop');
Route::get('/shop/category/{id}', [KategoriBarangControllers::class, 'filterByCategory'])->name('shop.category');
Route::get('/shop/barang/{id_barang}', [BarangControllers::class, 'showDetail'])->name('shop.detail_barang');

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
    Route::get('/Shop-Penitip', [BarangControllers::class, 'showShopPenitip'])->name('penitip.Shop-Penitip');
    // profil 
    Route::get('/profilPenitip', [PenitipControllrs::class, 'show'])->name('penitip.profilPenitip');
    //

    // categori dan deskripsi
    Route::get('/Penitip/categoryPembeli/{id}', [KategoriBarangControllers::class, 'filterByCategoryPenitip'])->name('penitip.categoryPenitip');
    Route::get('/Penitip/barang/{id_barang}', [BarangControllers::class, 'showDetailPenitip'])->name('penitip.detail_barangPenitip');
    //

    // logout
    Route::post('/logout-penitip', [AuthController::class, 'logoutPenitip'])->name('logout.penitip');
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
    Route::get('/Shop-Pembeli', [BarangControllers::class, 'showShopPembeli'])->name('pembeli.Shop-Pembeli');
    // profil
    Route::get('/profilPembeli', [PembeliControllrs::class, 'show'])->name('pembeli.profilPembeli');
    Route::put('/profilPembeli', [PembeliControllrs::class, 'update'])->name('pembeli.update');
    Route::put('/pembeli/update-profil/{id}', [PembeliControllrs::class, 'updateFoto'])->name('pembeli.updateProfil');
    //

    //alamat
    Route::get('/alamatPembeli', [AlamatControllers::class, 'showAlamat'])->name('pembeli.AlamatPembeli');
    Route::post('/alamatPembeli/store', [AlamatControllers::class, 'store'])->name('pembeli.storeAlamat');
    Route::put('/alamatPembeli/update/{id}', [AlamatControllers::class, 'update'])->name('pembeli.alamat.update');
    Route::delete('/alamatPembeli/delete/{id}', [AlamatControllers::class, 'destroy'])->name('pembeli.alamat.delete');
    Route::get('/alamatPembeli/search', [AlamatControllers::class, 'search'])->name('pembeli.alamat.search');
    //

    // categori dan desripsi barang
    Route::get('/Pembeli/categoryPembeli/{id}', [KategoriBarangControllers::class, 'filterByCategoryPembeli'])->name('pembeli.categoryPembeli');
    Route::get('/Pembeli/barang/{id_barang}', [BarangControllers::class, 'showDetailPembeli'])->name('pembeli.detail_barangPembeli');
    //

    // logout
    Route::post('/logout-pembeli', [AuthController::class, 'logoutPembeli'])->name('logout.pembeli');
});

Route::get('/registerOrganisasi', [OrganisasiControllrs::class, 'showRegisterOrganisasi'])->name('registerOrganisasi');
Route::post('/registerOrganisasi', [OrganisasiControllrs::class, 'registerOrganisasi'])->name('registerOrganisasi');
Route::get('/loginOrganisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/loginOrganisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');

Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logoutOrganisasi');

Route::middleware(['organisasi'])->group(function () {
    Route::get('/donasi-organisasi', function () {
        return view('organisasi.donasi-organisasi');
    })->name('organisasi.donasi-organisasi');
});


//ADMIN
Route::get('/loginDashboard', [AuthController::class, 'showLoginFormPegawai'])->name('loginDashboard');
Route::post('/loginPegawai', [PegawaiControllers::class, 'login'])->name('loginPegawai.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [PegawaiControllers::class, 'showDashboard'])->name('dashboard');

Route::middleware(['checkjabatan:Admin'])->group(function () {
    Route::post('/admin/update-profil', [PegawaiControllers::class, 'updateProfilAdmin'])->name('admin.updateProfil');
    Route::get('/admin/Dashboard', [PegawaiControllers::class, 'showDashboard'])->name('admin.Dashboard');
    //pegawai
    Route::get('/DashboardPegawai', [PegawaiControllers::class, 'showlistPegawai'])->name('admin.DashboardPegawai');
    Route::post('/DashboardPegawai/register', [PegawaiControllers::class, 'registerPegawai'])->name('admin.pegawai.register');
    Route::put('/DashboardPegawai/update/{id}', [PegawaiControllers::class, 'update'])->name('admin.pegawai.update');
    Route::delete('/DashboardPegawai/delete/{id}', [PegawaiControllers::class, 'destroy'])->name('admin.pegawai.delete');
    //jabatan
    Route::get('/admin/DashboardJabatan', [JabatanControllers::class, 'jabatanView'])->name('admin.DashboardJabatan');
    Route::post('/admin/DashboardJabatan/store', [JabatanControllers::class, 'store'])->name('admin.jabatan.store');
    Route::put('/admin/DashboardJabatan/update/{id}', [JabatanControllers::class, 'update']);
    Route::delete('/admin/DashboardJabatan/delete/{id}', [JabatanControllers::class, 'destroy'])->name('admin.jabatan.delete');
});

//Owner
Route::middleware(['auth:pegawai'])->group(function () {
    Route::get('/dashboard-owner', [OwnerControllers::class, 'showDashboard'])->name('owner.DashboardOwner');
    Route::get('/dashboard-donasi', [OwnerControllers::class, 'showDonasiDashboard'])->name('owner.DashboardDonasi');
    Route::post('/dashboard-donasi/update/{id_barang}/{id_request}', [OwnerControllers::class, 'updateDonasi'])->name('owner.UpdateDonasi');
    Route::get('/dashboard-history-donasi', [OwnerControllers::class, 'showHistoryDonasi'])->name('owner.DashboardHistoryDonasi');
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

// Menampilkan form reset password
Route::get('/resetPasswordOrganisasi/{token}', [lupaPasswordOrganisasiControllers::class, 'showResetPasswordForm'])->name('resetPasswordOrganisasi');


// Menangani form reset password
Route::post('/resetPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'resetPasswordOrganisasi'])->name('resetPasswordOrganisasi.post');
