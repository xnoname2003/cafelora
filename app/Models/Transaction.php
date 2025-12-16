<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function toppings()
    {
        return $this->hasManyThrough(
            TransactionItemTopping::class,
            TransactionItem::class,
            'transaction_id',
            'transaction_item_id',
            'id',
            'id'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'invoice',
        'status',
        'total',
        'paid_amount',
        'change_amount',
        'queue_number',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'invoice';
    }
}
