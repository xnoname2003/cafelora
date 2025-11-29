<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Topping;
use App\Models\Variant;


class Menu extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'base_price',
        'image',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toppings()
    {
        return $this->belongsToMany(Topping::class, 'menu_topping');
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'menu_variant');
    }
}
