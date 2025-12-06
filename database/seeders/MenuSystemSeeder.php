<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Topping;
use Illuminate\Database\Seeder;

class MenuSystemSeeder extends Seeder
{
    public function run(): void
    {
        //categories
        $categories = [
            'Coffee',
            'Tea',
            'Milk Based',
            'Snack',
            'Dessert'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        //toppings
        $toppings = [
            ['name' => 'Extra Sugar', 'price' => 2000],
            ['name' => 'Brown Sugar', 'price' => 3000],
            ['name' => 'Cheese Foam', 'price' => 5000],
            ['name' => 'Choco Chips', 'price' => 4000],
            ['name' => 'Boba', 'price' => 4000],
            ['name' => 'Ice Cream', 'price' => 6000],
            ['name' => 'Whipped Cream', 'price' => 5000],
            ['name' => 'Caramel', 'price' => 3000],
            ['name' => 'Oreo Crush', 'price' => 3000],
            ['name' => 'Grass Jelly', 'price' => 4000],
        ];

        foreach ($toppings as $t) {
            Topping::firstOrCreate($t);
        }

        // menu items
        $menuNames = [
            'Americano', 'Cappuccino', 'Cafe Latte', 'Mocha',
            'Vanilla Latte', 'Hazelnut Latte', 'Thai Tea', 'Matcha Latte',
            'Milkshake Chocolate', 'Milkshake Strawberry',
            'French Fries', 'Chicken Popcorn', 'Spicy Wings',
            'Brownies', 'Cheesecake',
            'Black Tea', 'Green Tea', 'Lemon Tea'
        ];

        foreach ($menuNames as $name) {
            $baseUrl = 'https://placehold.co/1920x1080?text=';
            $fontImage = '&font=roboto';
            $menu = Menu::create([
                'category_id' => Category::inRandomOrder()->first()->id,
                'name' => $name,
                'description' => "Delicious $name from our cafe.",
                'base_price' => rand(15000, 35000),
                'stock' => rand(5, 30),
                'sales_qty' => rand(5, 30),
                'image' => $baseUrl . urlencode($name) . $fontImage,
            ]);

            // Relasi topping acak 0–3 topping
            $randomToppings = Topping::inRandomOrder()
                ->limit(rand(0, 3))
                ->pluck('id')
                ->toArray();

            $menu->toppings()->sync($randomToppings);
        }
    }
}
