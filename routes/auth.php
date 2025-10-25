<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
     Route::get('login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('forgot-password', \App\Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', \App\Livewire\Auth\ResetPassword::class)->name('password.reset');
});
Route::middleware('auth')->group(function () {
    Route::get('verify-email', \App\Livewire\Auth\VerifyEmail::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', \App\Http\Controllers\Auth\VerifyEmailController::class)
        ->middleware(['signed','throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', \App\Livewire\Auth\ConfirmPassword::class)->name('password.confirm');
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
});
