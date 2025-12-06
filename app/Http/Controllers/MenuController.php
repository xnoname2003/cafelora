<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function indexCustomer()
    {
        // Ambil kategori beserta semua menu yang terkait
        $categories = Category::with([
            'menus.variants',
            'menus.toppings'
        ])->orderBy('name')->get();

        return view('customer.menu', compact('categories'));
    }
}
