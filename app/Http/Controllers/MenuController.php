<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function indexCustomer(Request $request)
    {
        // ... (Kode untuk mengambil parameter search, min_price, max_price, categoryId, dan $isSearching tetap sama) ...
        $searchQuery = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $categoryId = $request->input('category_id');
        $isSearching = $searchQuery || $minPrice || $maxPrice || $categoryId;
        $allCategories = Category::orderBy('name')->get();


        if ($isSearching) {
            
            // Logika Pencarian: Tetap sama, tidak ada perubahan
            $query = Menu::query();

            if ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%'); 
            }
            if ($minPrice && is_numeric($minPrice)) {
                $query->where('base_price', '>=', $minPrice); 
            }
            if ($maxPrice && is_numeric($maxPrice)) {
                $query->where('base_price', '<=', $maxPrice); 
            }
            if ($categoryId) {
                $query->where('category_id', $categoryId); 
            }
            // 💡 Catatan: Hasil pencarian di sini tidak diurutkan berdasarkan sales_qty, 
            // karena user mungkin mencari menu yang spesifik.
            $filteredMenus = $query->with(['variants', 'toppings'])->get();

            return view('customer.menu', [
                'filteredMenus' => $filteredMenus,
                'isSearching' => true,
                'categories' => collect(), 
                'allCategories' => $allCategories, 
            ]);

        } else {
            
            // Logika Tampilan Normal
            $categories = Category::with([
                'menus' => function ($query) {
                    // 🚀 PERUBAHAN UTAMA DI SINI: Urutkan menu berdasarkan sales_qty (terbanyak ke terkecil)
                    $query->orderBy('sales_qty', 'desc');
                },
                'menus.variants',
                'menus.toppings'
            ])
            ->orderBy('name')
            ->get();
            
            return view('customer.menu', [
                'categories' => $categories,
                'isSearching' => false,
                'filteredMenus' => collect(), 
                'allCategories' => $allCategories, 
            ]);
        }
    }
}