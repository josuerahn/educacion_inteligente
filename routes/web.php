<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::view('/', 'home')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/landing', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // <-- ajusta el nombre de la ruta aquí si hace falta
    }
    return view('home'); // resources/views/home.blade.php
})->name('landing');

Route::view('/acerca-utn', 'acerca-utn')->name('acerca-utn');
require __DIR__.'/auth.php';
