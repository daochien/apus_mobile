<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;

Route::get('login', [AuthController::class, 'login'])->name('admin.login');
Route::post('sign-in', [AuthController::class, 'signIn'])->name('admin.signIn');
Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
