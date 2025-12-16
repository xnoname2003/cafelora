<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class PosTransactionController extends Controller
{
    public function show($invoice)
    {
        // Ambil transaksi berdasarkan invoice
        $transaction = Transaction::with(['user', 'items.menu'])->where('invoice', $invoice)->firstOrFail();

        $snapToken = null;

        // Jika status belum paid, generate Snap Token
        if ($transaction->status !== 'paid') {
            // Pastikan konfigurasi Midtrans sudah sesuai di config/midtrans.php atau .env
            // Dan package midtrans/midtrans-php sudah terinstall
            
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->invoice, // Gunakan invoice sebagai Order ID
                    'gross_amount' => (int) $transaction->total,
                ],
                'customer_details' => [
                    'first_name' => $transaction->user->name ?? 'Guest',
                    'email' => $transaction->user->email ?? 'guest@example.com',
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Handle error jika koneksi ke Midtrans gagal
                // Log::error($e->getMessage());
            }
        }

        return view('filament.pages.detail-order', compact('transaction', 'snapToken'));
    }
}