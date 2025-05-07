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

// Route::get('/organisasi', [OrganisasiControllrs::class, 'index'])->name('organisasi.index');
// Route::get('/organisasi/create', [OrganisasiControllrs::class, 'create'])->name('organisasi.create');
// Route::post('/organisasi', [OrganisasiControllrs::class, 'store'])->name('organisasi.store');
// Route::get('/organisasi/{id}', [OrganisasiControllrs::class, 'show'])->name('organisasi.show');
// Route::get('/organisasi/{id}/edit', [OrganisasiControllrs::class, 'edit'])->name('organisasi.edit');
// Route::put('/organisasi/{id}', [OrganisasiControllrs::class, 'update'])->name('organisasi.update');
// Route::delete('/organisasi/{id}', [OrganisasiControllrs::class, 'destroy'])->name('organisasi.destroy');
// Route::get('/organisasi-search', [OrganisasiControllrs::class, 'search'])->name('organisasi.search');
Route::get('/login-organisasi', [AuthController::class, 'showLoginOrganisasi'])->name('loginOrganisasi');
Route::post('/login-organisasi', [AuthController::class, 'loginOrganisasi'])->name('loginOrganisasi.post');
Route::post('/logout-organisasi', [AuthController::class, 'logoutOrganisasi'])->name('logoutOrganisasi');

Route::get('/dashboard-organisasi', function () {
    $organisasi = session('organisasi');
    if (!$organisasi) return redirect()->route('loginOrganisasi');
    return view('organisasi.donasi', compact('organisasi'));
})->name('dashboardOrganisasi');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/organisasi/me', function (Request $request) {
        return response()->json([
            'message' => 'Data organisasi login berhasil diambil',
            'organisasi' => $request->user()
        ]);
    });
    Route::put('/updateorganisasi', [OrganisasiControllrs::class, 'update']);
    Route::get('/showorganisasi', [OrganisasiControllrs::class, 'showLogin']);
});
