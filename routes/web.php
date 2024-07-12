<?php

use App\Application\Route\Route;

Route::get('/', function () {
    echo "Working!";
})->middleware('auth')->name('home.index');

Route::view('/auth/login', 'login')->name('login.index')->middleware('guest');
Route::post('/auth/login', [\App\Controllers\LoginController::class, 'store'])->name('login.store');