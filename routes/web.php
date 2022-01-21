<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return inertia('Dashboard');
    })->name('home');
});

Route::get('/privacy', function () {
    return view('home.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('home.terms');
})->name('terms');
