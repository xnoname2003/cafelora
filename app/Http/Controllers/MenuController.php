<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    public function indexCustomer(Request $request)
    {
        $searchQuery = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $categoryName = $request->input('category'); 

        $isSearching = $searchQuery || $minPrice || $maxPrice || $categoryName;
        
        $allCategories = Category::orderBy('name')->get();

        if ($isSearching) {
            
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
            
            if ($categoryName) {
                $category = Category::where('name', $categoryName)->first(); 

                if ($category) {
                    $query->where('category_id', $category->id); 
                } else {
                    $query->whereRaw('1 = 0'); 
                }
            }
            
            $filteredMenus = $query->with(['variants', 'toppings', 'category'])->get();
            
            $firstMenuId = $filteredMenus->isNotEmpty() ? $filteredMenus->first()->id : null;


            return view('customer.menu', [
                'filteredMenus' => $filteredMenus,
                'isSearching' => true,
                'categories' => collect(), 
                'allCategories' => $allCategories, 
                'firstMenuId' => $firstMenuId,
            ]);

        } else {
            
            $categories = Category::with([
                'menus' => function ($query) {
                    $query->orderBy('sales_qty', 'desc')->with(['variants', 'toppings']);
                }
            ])
            ->orderBy('name')
            ->get();
            
            return view('customer.menu', [
                'categories' => $categories,
                'isSearching' => false,
                'filteredMenus' => collect(), 
                'allCategories' => $allCategories,
<<<<<<< Updated upstream
=======
                'firstMenuId' => null,
>>>>>>> Stashed changes
            ]);
        }
    }

<<<<<<< Updated upstream
    public function showCustomer($id)
    {
        $menu = Menu::findOrFail($id);
=======
    public function showCustomer(Menu $menu)
    {
        $menu->load(['category', 'variants', 'toppings']);
>>>>>>> Stashed changes

        return view('customer.menu-detail', [
            'menu' => $menu,
        ]);
    }
    
    public function showByCategory($name)
    {
        $category = Category::where('name', $name)->firstOrFail();
<<<<<<< Updated upstream

        $menus = Menu::where('category_id', $category->id)
                      ->with(['variants', 'toppings', 'category'])
                      ->get();

        $allCategories = Category::orderBy('name')->get();
        
=======
        
        $menus = Menu::where('category_id', $category->id)
                      ->with(['variants', 'toppings', 'category'])
                      ->orderBy('sales_qty', 'desc')
                      ->get();
                      
        $allCategories = Category::orderBy('name')->get();
        
        $firstMenuId = $menus->isNotEmpty() ? $menus->first()->id : null;
        
>>>>>>> Stashed changes
        return view('customer.menu', [
            'filteredMenus' => $menus,
            'isSearching' => true, 
            'categories' => collect(), 
            'allCategories' => $allCategories, 
<<<<<<< Updated upstream
=======
            'firstMenuId' => $firstMenuId,
>>>>>>> Stashed changes
        ]);
    }
}