<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', [MenuController::class, 'indexCustomer'])->name('customer.menu.index');

Route::get('/category/{name}', [MenuController::class, 'showByCategory'])->name('customer.category.show');

Route::get('/menu/{menu}', [MenuController::class, 'showCustomer'])->name('customer.menu.show');