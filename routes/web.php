<?php

use App\Application\Route\Route;

Route::get('/', [\App\Controllers\HomeController::class, 'index'])
    ->middleware('auth')
    ->name('home.index');

Route::view('/auth/login', 'login')->name('login.index')
    ->middleware('guest');
Route::post('/auth/login', [\App\Controllers\LoginController::class, 'store'])
    ->name('login.store');
Route::post('/auth/logout', [\App\Controllers\LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::post('/news', [\App\Controllers\NewsController::class, 'store'])
    ->name('news.store')
    ->middleware('auth');
Route::put('/news/{id}', [\App\Controllers\NewsController::class, 'update'])
    ->name('news.update')
    ->middleware('auth');
Route::delete('/news/{id}', [\App\Controllers\NewsController::class, 'destroy'])
    ->name('news.destroy')
    ->middleware('auth');