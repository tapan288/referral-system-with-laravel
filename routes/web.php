<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralsController;
use App\Http\Middleware\RedirectIfNoReferralCode;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/referrals', [ReferralsController::class, 'index'])
        ->middleware(RedirectIfNoReferralCode::class)
        ->name('referrals.index');
});

require __DIR__ . '/auth.php';

Route::get('/referrals/{referralCode:code}', [ReferralsController::class, 'show'])
    ->name('referrals.show');
Route::post('/referrals/{referralCode:code}', [ReferralsController::class, 'store'])
    ->name('referrals.store');
