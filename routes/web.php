<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', [MenuController::class, 'indexCustomer'])->name('customer.menu.index');

Route::get('/menu/{id}', [MenuController::class, 'showCustomer'])->name('customer.menu.show');