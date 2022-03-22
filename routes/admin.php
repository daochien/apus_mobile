<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\CommonController;

Route::get('login', [AuthController::class, 'login'])->name('admin.login');
Route::post('sign-in', [AuthController::class, 'signIn'])->name('admin.signIn');
Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::group(['middleware' => ['auth']], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    Route::prefix('common')->group(function() {
        Route::post('upload', [CommonController::class, 'upload'])->name('admin.common.upload');
    });

    Route::prefix('sources')->group(function() {
        Route::get('', [SourceController::class, 'index'])->name('admin.dashboard.index');
        Route::post('', [SourceController::class, 'store'])->name('admin.dashboard.store');
        Route::get('create', [SourceController::class, 'create'])->name('admin.dashboard.create');
        Route::get('{id}', [SourceController::class, 'edit'])->name('admin.dashboard.edit');
        Route::post('{id}', [SourceController::class, 'update'])->name('admin.dashboard.update');
        Route::delete('{id}', [SourceController::class, 'destroy'])->name('admin.dashboard.destroy');
    });

});

