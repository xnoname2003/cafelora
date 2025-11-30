<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function indexCustomer()
    {
        $menus = Menu::with(['category', 'variants', 'toppings'])->get();

        return view('customer.menu', compact('menus'));
    }
}
