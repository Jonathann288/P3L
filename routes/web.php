<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('beranda');
})->name('beranda');

Route::get('/shop', function () {
    return view('shop');
})->name('shop');

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
