<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Topping;
use App\Models\Variant;
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

        //variants
        $variants = [
            ['name' => 'regular', 'price_adjustment' => 0],
            ['name' => 'large', 'price_adjustment' => 5000],
            ['name' => 'hot', 'price_adjustment' => 0],
            ['name' => 'ice', 'price_adjustment' => 3000],
        ];

        foreach ($variants as $v) {
            Variant::firstOrCreate($v);
        }

        $menuItems = require database_path('seeders/data/menu_items.php');

        foreach ($menuItems as $item) {
            $categoryId = Category::firstOrCreate(['name' => $item['category']])->id;

            $menu = Menu::updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'base_price' => $item['base_price'],
                    'stock' => rand(5, 30),
                    'sales_qty' => rand(5, 30),
                    'image' => $item['image'],
                ]
            );

            $toppingIds = Topping::whereIn('name', $item['toppings'])->pluck('id')->toArray();
            $menu->toppings()->sync($toppingIds);

            $variantIds = Variant::whereIn('name', $item['variants'])->pluck('id')->toArray();
            $menu->variants()->sync($variantIds);
        }
    }
}
