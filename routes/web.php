<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\MemberController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SaleController;
use App\Http\Controllers\Web\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('products', ProductController::class)->except(['show', 'create']);
    Route::resource('members', MemberController::class)->except(['show', 'create']);
    Route::resource('employees', EmployeeController::class)->except(['show', 'create']);

    Route::get('/pos', [SaleController::class, 'create'])->name('pos');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    Route::get('/reports', ReportController::class)->name('reports');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
