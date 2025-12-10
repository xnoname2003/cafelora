<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function updated(Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            foreach ($transaction->items as $item) {
                $item->menu->decrement('stock', $item->quantity);
            }
        }
    }
}
