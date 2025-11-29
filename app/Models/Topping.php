<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Topping extends Model
{
    protected $fillable = ['name', 'price'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_topping');
    }
}
