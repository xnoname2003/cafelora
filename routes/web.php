<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PosTransactionController;
use App\Http\Controllers\MidtransWebhookController;

// 1. Rute Utama (index)
Route::get('/', [MenuController::class, 'indexCustomer'])->name('customer.menu.index');

// 2. Rute Filter Berdasarkan Kategori
Route::get('/category/{name}', [MenuController::class, 'showByCategory'])->name('customer.category.show');

// 3. Rute Detail Menu (Menggunakan Model Binding {menu})
Route::get('/menu/{menu}', [MenuController::class, 'showCustomer'])->name('customer.menu.show');

Route::get('/receipt/{transaction}', [ReceiptController::class, 'show'])
    ->name('receipt.show');

Route::post('/midtrans/notify', [MidtransWebhookController::class, 'notify']);
