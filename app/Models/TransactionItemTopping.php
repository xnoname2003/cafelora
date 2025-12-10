<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemTopping extends Model
{
    protected $fillable = [
        'transaction_item_id',
        'topping_id',
        'price',
        'quantity',
    ];
    
    public function transactionItem() 
    { 
        return $this->belongsTo(TransactionItem::class); 
    }

    public function topping() 
    { 
        return $this->belongsTo(Topping::class); 
    }
}
