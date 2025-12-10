<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'menu_id',
        'variant_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function transaction() 
    { 
        return $this->belongsTo(Transaction::class); 
    }

    public function menu() 
    { 
        return $this->belongsTo(Menu::class); 
    }

    public function variant() 
    { 
        return $this->belongsTo(Variant::class); 
    }

    public function toppings() 
    { 
        return $this->hasMany(TransactionItemTopping::class); 
    }
}
