<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReceiptController extends Controller
{
    public function show(Transaction $transaction)
    {
        $transaction->load(['items.menu', 'items.variant', 'user']);

        return view('receipt.show', compact('transaction'));
    }

}
