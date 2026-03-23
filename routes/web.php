<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\RouterController;

// Protected Routes (Butuh Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routers Management (Fase 2)
    Route::get('/routers', [RouterController::class, 'index'])->name('routers.index');
    Route::post('/routers', [RouterController::class, 'store'])->name('routers.store');
    Route::delete('/routers/{router}', [RouterController::class, 'destroy'])->name('routers.destroy');
    Route::post('/routers/{router}/test', [RouterController::class, 'testConnection'])->name('routers.test');

    // Hotspot Voucher System (Fase 3)
    Route::get('/hotspot/users', function() { return inertia('Radius/Hotspot/Users'); })->name('hotspot.users');
    Route::get('/hotspot/profiles', function() { return inertia('Radius/Hotspot/Profiles'); })->name('hotspot.profiles');
    Route::get('/hotspot/generate', function() { return inertia('Radius/Hotspot/Generate'); })->name('hotspot.generate');
    Route::get('/hotspot/print', function() { return inertia('Radius/Hotspot/Print'); })->name('hotspot.print');
    Route::get('/hotspot/templates', function() { return inertia('Radius/Hotspot/Templates'); })->name('hotspot.templates');

    Route::get('/vouchers', [App\Http\Controllers\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/generate', [App\Http\Controllers\VoucherController::class, 'generate'])->name('vouchers.generate');
    Route::post('/vouchers/batches/{batch}/activate', [App\Http\Controllers\VoucherController::class, 'activate'])->name('vouchers.activate');

    // PPPoE & Billing System (Fase 4)
    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');

    Route::get('/billing', [App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/invoices/{invoice}/pay', [App\Http\Controllers\BillingController::class, 'pay'])->name('billing.pay');
});
