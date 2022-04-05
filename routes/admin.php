<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SourceController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SourceConfigController;
use App\Http\Controllers\Admin\AppCustomerController;

Route::get('login', [AuthController::class, 'login'])->name('admin.login');
Route::post('sign-in', [AuthController::class, 'signIn'])->name('admin.signIn');
Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::group(['middleware' => ['auth']], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    Route::prefix('common')->group(function() {
        Route::post('upload', [CommonController::class, 'upload'])->name('admin.common.upload');
    });

    Route::prefix('sources')->group(function() {
        Route::get('', [SourceController::class, 'index'])->name('admin.sources.index');
        Route::post('', [SourceController::class, 'store'])->name('admin.sources.store');
        Route::get('create', [SourceController::class, 'create'])->name('admin.sources.create');
        Route::get('{id}', [SourceController::class, 'edit'])->name('admin.sources.edit');
        Route::post('{id}', [SourceController::class, 'update'])->name('admin.sources.update');
        Route::delete('{id}', [SourceController::class, 'destroy'])->name('admin.sources.destroy');
        Route::post('export/{id}', [SourceController::class, 'export'])->name('admin.sources.export');
    });

    Route::prefix('source-configs')->group(function() {
        Route::get('', [SourceConfigController::class, 'index'])->name('admin.source_configs.index');
        Route::post('', [SourceConfigController::class, 'store'])->name('admin.source_configs.store');
        Route::get('create', [SourceConfigController::class, 'create'])->name('admin.source_configs.create');
        Route::get('{id}', [SourceConfigController::class, 'edit'])->name('admin.source_configs.edit');
        Route::post('{id}', [SourceConfigController::class, 'update'])->name('admin.source_configs.update');
        Route::delete('{id}', [SourceConfigController::class, 'destroy'])->name('admin.source_configs.destroy');
    });

    Route::prefix('packages')->group(function() {
        Route::get('', [PackageController::class, 'index'])->name('admin.packages.index');
        Route::post('', [PackageController::class, 'store'])->name('admin.packages.store');
        Route::get('create', [PackageController::class, 'create'])->name('admin.packages.create');
        Route::get('{id}', [PackageController::class, 'edit'])->name('admin.packages.edit');
        Route::post('{id}', [PackageController::class, 'update'])->name('admin.packages.update');
        Route::delete('{id}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
    });

    Route::prefix('app-customers')->group(function() {
        Route::get('', [AppCustomerController::class, 'index'])->name('admin.app_customer.index');
        Route::post('', [AppCustomerController::class, 'store'])->name('admin.app_customer.store');
        Route::get('create', [AppCustomerController::class, 'create'])->name('admin.app_customer.create');
        Route::get('{id}', [AppCustomerController::class, 'edit'])->name('admin.app_customer.edit');
        Route::post('{id}', [AppCustomerController::class, 'update'])->name('admin.app_customer.update');
        Route::delete('{id}', [AppCustomerController::class, 'destroy'])->name('admin.app_customer.destroy');
    });

});

