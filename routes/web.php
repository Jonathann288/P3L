<?php

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
use App\Http\Controllers\RequestDonasiControllers;
use App\Http\Controllers\DiskusiControllers;
use App\Http\Controllers\DonasiControllers;
use App\Http\Controllers\TransaksiPenitipanControllers;

Route::get('/', function () {
    return view('beranda');
})->name('beranda');
Route::get('/shop/barang/', function () {
    return view('shop.detail_barang');
})->name('shop.detail_barang');

Route::get('/shop', [BarangControllers::class, 'showShop'])->name('shop');
Route::get('/shop/category/{id}', [KategoriBarangControllers::class, 'filterByCategory'])->name('shop.category');
Route::get('/shop/barang/{id_barang}', [BarangControllers::class, 'showDetail'])->name('shop.detail_barang');
Route::get('/shop/search', [BarangControllers::class, 'search'])->name('barang.search');


Route::get('/penitip', function () {
    return view('penitip');
})->name('penitip');

Route::get('/donasi', [BarangControllers::class, 'showDonasi'])->name('donasi');
Route::get('/donasi/barang/{id_barang}', [BarangControllers::class, 'showDetailDonasi'])->name('donasi.detail_barang_donasi');

Route::get('/requestBarang', function () {
    return view('requestBarang');
})->name('requestBarang');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginGabungan'])->name('login.proses');

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

    // Barang titipan penitip
    Route::get('/Penitip/barang-titipan', [TransaksiPenitipanControllers::class, 'showBarangTitipan'])->name('penitip.barang-titipan');
    Route::get('/penitip/barang/search', [TransaksiPenitipanControllers::class, 'search'])->name('penitip.barang.search');
    Route::post('/barang-titipan/perpanjang/{id}', [TransaksiPenitipanControllers::class, 'perpanjangMasaPenitipan'])->name('penitip.barang.perpanjang');


    // logout
    Route::post('/logout-penitip', [AuthController::class, 'logoutPenitip'])->name('logout.penitip');
});


Route::get('/registerPembeli', [PembeliControllrs::class, 'showRegisterForm'])->name('registerPembeliForm');
Route::post('/registerPembeli', [PembeliControllrs::class, 'registerPembeli'])->name('registerPembeli');

Route::get('/registerPenitip', [PenitipControllrs::class, 'showRegisterFormPenitip'])->name('registerPenitipForm');
Route::post('/registerPenitip', [PenitipControllrs::class, 'registerPenitip'])->name('registerPenitip.post');

// PEMBELI
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

    Route::get('/historyPembeli', [PembeliControllrs::class, 'showHistory'])->name('pembeli.historyPembeli');
    //

    Route::post('/diskusi', [DiskusiControllers::class, 'store'])->name('diskusi.store');

    // logout
    Route::post('/logout-pembeli', [AuthController::class, 'logoutPembeli'])->name('logout.pembeli');

    Route::get('/pembeli/cart', function () {
        return view('pembeli.cartPembeli');
    })->name('pembeli.cart')->middleware('auth:pembeli');
    
    Route::post('/pembeli/rating/submit', [PembeliControllrs::class, 'submitRating'])->name('pembeli.submitRating');
});

Route::get('/registerOrganisasi', [OrganisasiControllrs::class, 'showRegisterOrganisasi'])->name('registerOrganisasi');
Route::post('/registerOrganisasi', [OrganisasiControllrs::class, 'registerOrganisasi'])->name('registerOrganisasi.store');
Route::get('/loginOrganisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/loginOrganisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');


Route::middleware(['organisasi'])->group(function () {
    // COPY INI
    Route::get('/donasi-organisasi', [BarangControllers::class, 'showDonasiOrganisasi'])->name('organisasi.donasi-organisasi');
    Route::get('/donasi-organisasi//barang/{id_barang}', [BarangControllers::class, 'showDetailDonasiOranisasi'])->name('organisasi.detail_barang_donasi');
    //SAMPE INI
    Route::get('/organisasi/profilOrganisasi', [OrganisasiControllrs::class, 'showOrganisasi'])->name('organisasi.profilOrganisasi');
    Route::post('/organisasi/update-profil', [OrganisasiControllrs::class, 'updateProfil'])->name('organisasi.updateProfil');
    Route::get('/organisasi/requestDonasiOrganisasi', [RequestDonasiControllers::class, 'requestDonasiOrganisasi'])->name('organisasi.requestDonasiOrganisasi');
    Route::put('/request-donasi/update/{id}', [RequestDonasiControllers::class, 'update'])->name('organisasi.request.update');
    Route::delete('/organisasi/destroy/{id}', [RequestDonasiControllers::class, 'destroy'])->name('organisasi.destroy');
    Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logout.organisasi');

    Route::get('/request-barang', [RequestDonasiControllers::class, 'create'])->name('requestBarang.create');
    Route::post('/request-barang', [RequestDonasiControllers::class, 'store'])->name('requestBarang.store');

});

Route::post('/loginDashboard', [AuthController::class, 'loginPegawai'])->name('loginPegawai.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['checkjabatan:Admin'])->group(function () {
    Route::post('/logout-Admin', [AuthController::class, 'logoutPegawai'])->name('logout.pegawai');

    Route::get('/Dashboard', [PegawaiControllers::class, 'showLoginAdmin'])->name('admin.Dashboard');
    Route::get('/DashboardPegawai', [PegawaiControllers::class, 'showlistPegawai'])->name('admin.DashboardPegawai');
    Route::post('/DashboardPegawai/register', [PegawaiControllers::class, 'registerPegawai'])->name('admin.pegawai.register');
    Route::put('/DashboardPegawai/update/{id}', [PegawaiControllers::class, 'update'])->name('admin.pegawai.update');
    Route::delete('/DashboardPegawai/delete/{id}', [PegawaiControllers::class, 'destroy'])->name('admin.pegawai.delete');
    Route::get('/DashboardPegawai/search', [PegawaiControllers::class, 'searchPegawai'])->name('admin.pegawai.search');


    Route::get('/DashboardOrganisasi', [OrganisasiControllrs::class, 'showlistOrganisasi'])->name('admin.DashboardOrganisasi');
    Route::put('/DashboardOrganisasi/update/{id}', [OrganisasiControllrs::class, 'update'])->name('admin.organisasi.update');
    Route::delete('/DashboardOrganisasi/delete/{id}', [OrganisasiControllrs::class, 'destroy'])->name('admin.organisasi.delete');
    Route::get('/DashboardOrganisasi/search', [OrganisasiControllrs::class, 'search'])->name('admin.organisasi.search');

    Route::put('/DashboardPegawai/reset-password/{id}', [PegawaiControllers::class, 'resetPassword'])->name('admin.pegawai.resetPassword');
});
Route::middleware(['checkjabatan:Owner'])->group(function () {
    Route::post('/logout-Owner', [AuthController::class, 'logoutPegawai'])->name('logout.pegawai');

    Route::get('/DashboardOwner', [PegawaiControllers::class, 'showLoginOwner'])->name('owner.DashboardOwner');
    Route::get('/DashboardDonasi', [RequestDonasiControllers::class, 'showlistRequestDonasi'])->name('owner.DashboardDonasi');
    Route::post('/owner/donasi/submit', [DonasiControllers::class, 'submitDonasi'])->name('owner.donasi.submit');
    Route::put('/owner/donasi/reject', [DonasiControllers::class, 'rejectDonasi'])->name('owner.donasi.reject');

    Route::get('/DashboardHistoryDonasi/history-donasi', [DonasiControllers::class, 'historyDonasi'])->name('owner.historyDonasi');
    Route::post('/owner/historyDonasi/update/{id_request}', [DonasiControllers::class, 'updateDonasi'])->name('owner.historyDonasi.update');



});
Route::middleware(['checkjabatan:Customer Service'])->group(function () {
    Route::post('/logout-CS', [AuthController::class, 'logoutPegawai'])->name('logout.pegawai');

    Route::get('/DashboardCS', [PegawaiControllers::class, 'showLoginCS'])->name('CustomerService.DashboardCS');
    Route::get('/DashboardPenitip', [PenitipControllrs::class, 'showlistPenitip'])->name('CustomerService.DashboardPenitip');
    Route::post('/DashboardPenitip/store', [PenitipControllrs::class, 'registerPenitip'])->name('CustomerService.penitip.register');
    Route::put('/DashboardPenitip/update/{id}', [PenitipControllrs::class, 'update'])->name('CustomerService.penitip.update');
    Route::delete('/DashboardPenitip/delete/{id}', [PenitipControllrs::class, 'destroy'])->name('CustomerService.penitip.destroy');
    Route::get('/DashboardPenitip/search', [PenitipControllrs::class, 'search'])->name('CustomerService.penitip.search');
});
Route::middleware(['checkjabatan:Gudang'])->group(function () {
    // COPY INI 
    Route::get('/DashboardGudang', [PegawaiControllers::class, 'showLoginGudang'])->name('gudang.DashboardGudang');

    Route::get('/DashboardTitipanBarang', [TransaksiPenitipanControllers::class, 'showTitipanBarang'])->name('gudang.DashboardTitipanBarang');
    Route::get('/SearchTitipan', [TransaksiPenitipanControllers::class, 'searchTitipan'])->name('gudang.SearchTitipan');

    // Tambah Transaksi Baru
    Route::get('/CreateTitipanBarang', [TransaksiPenitipanControllers::class, 'createTitipanBarang'])->name('gudang.CreateTitipanBarang');
    Route::post('/StoreTitipanBarang', [TransaksiPenitipanControllers::class, 'storeTitipanBarang'])->name('gudang.StoreTitipanBarang');

    // Update dan Delete
    Route::put('/UpdateTitipanBarang/{id}', [TransaksiPenitipanControllers::class, 'updateTitipanBarang'])->name('gudang.UpdateTitipanBarang');
    Route::delete('/DeleteTitipanBarang/{id}', [TransaksiPenitipanControllers::class, 'deleteTitipanBarang'])->name('gudang.DeleteTitipanBarang');

    // API Endpoints
    Route::get('/api/getDurasiPenitipan', [TransaksiPenitipanControllers::class, 'getDurasiPenitipan'])->name('gudang.getDurasiPenitipan');

    //Cetak nota skuy
    Route::get('/cetak-nota/{id}', [TransaksiPenitipanControllers::class, 'cetakNota'])->name('gudang.CetakNota');

    // Daftar Barang Management
    Route::get('/DaftarBarang', [BarangControllers::class, 'showDaftarBarang'])->name('gudang.DaftarBarang');
    Route::get('/DetailBarang/{id_barang}', [BarangControllers::class, 'showDetailBarangGudang'])->name('gudang.DetailBarang');
    Route::get('/EditBarang/{id_barang}', [BarangControllers::class, 'showEditBarang'])->name('gudang.EditBarang');
    Route::put('/UpdateBarang/{id_barang}', [BarangControllers::class, 'updateBarangGudang'])->name('gudang.UpdateBarang');
    // SAMPE INI 

    //ini
    Route::get('/PerpanjanganMasaPenitipan', [TransaksiPenitipanControllers::class, 'showPerpanjanganPage'])->name('gudang.showPerpanjanganPage');
    Route::post('/ProsesPerpanjangPenitipan/{id_transaksi_penitipan}', [TransaksiPenitipanControllers::class, 'prosesPerpanjangPenitipan'])->name('gudang.prosesPerpanjangPenitipan');
    Route::get('/PerpanjanganMasaPenitipan', [TransaksiPenitipanControllers::class, 'showPerpanjanganPage'])->name('gudang.showPerpanjanganPage');

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

// Route::get('/resetPasswordOrganisasi', function () {
//     return view('LupaPasswordOrg.resetPasswordOrganisasi');
// })->name('LupaPasswordOrg.resetPasswordOrganisasi');

// Route::get('/resetPasswordPembeli', function () {
//     return view('LupaPasswordPembeli.resetPasswordPembeli');
// })->name('LupaPasswordPembeli.resetPasswordPembeli');



// Route::get('/lupaPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'showLinkForm'])->name('LupaPasswordOrg.lupaPasswordOrganisasi');
// Route::post('/lupaPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'lupaPasswordOrganisasiPost'])->name('LupaPasswordOrg.lupaPasswordOrganisasi.post');
// Route::get('/pesanLupaPasswordOrganisasi/{token}', [lupaPasswordOrganisasiControllers::class, 'showLinkForm'])->name('password.forgot.link');

// Route::get('/lupaPasswordPembeli', [lupaPasswordPembeliControllers::class, 'showLinkFormPembeli'])->name('LupaPasswordPembeli.lupaPasswordPembeli');
// Route::post('/lupaPasswordPembeli', [lupaPasswordPembeliControllers::class, 'lupaPasswordPembeliPost'])->name('LupaPasswordPembeli.lupaPasswordPembeli.post');
// Route::get('/pesanLupaPasswordPembeli/{token}', [lupaPasswordPembeliControllers::class, 'showLinkFormPembeli'])->name('password.forgot.link');

// // Menampilkan form reset password
// Route::get('/resetPasswordOrganisasi/{token}', [lupaPasswordOrganisasiControllers::class, 'showResetPasswordForm'])->name('resetPasswordOrganisasi');


// // Menangani form reset password
// Route::post('/resetPasswordOrganisasi', [lupaPasswordOrganisasiControllers::class, 'resetPasswordOrganisasi'])->name('resetPasswordOrganisasi.post');

Route::get('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/validasiForgotPasswordAct', [AuthController::class, 'validasiForgotPasswordAct'])->name('validasiForgotPasswordAct');
Route::get('/validasiForgotPassword/{token}', [AuthController::class, 'validasiForgotPassword'])->name('validasiForgotPassword');
Route::post('/forgotPasswordAct', [AuthController::class, 'forgotPasswordAct'])->name('forgotPasswordAct');




//API
