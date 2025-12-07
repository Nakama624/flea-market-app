<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/address', function () {
    return view('address');
});

Route::get('/sell', function () {
    return view('sell');
});

Route::get('/mypage', function () {
    return view('mypage');
});

Route::get('/mypage/profile', function () {
    return view('profile');
});

