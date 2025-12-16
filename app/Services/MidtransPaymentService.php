<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Transaction;

class MidtransPaymentService
{
    public function apply(Transaction $trx, array $payload): void
    {
        $midtransStatus = $payload['transaction_status'] ?? null; // pending, settlement, cancel, deny, expire, capture
        $paymentType    = $payload['payment_type'] ?? 'midtrans'; // qris, bank_transfer, gopay, dll

        $status = match ($midtransStatus) {
            'settlement', 'capture' => 'paid',
            'pending'              => 'pending',
            'cancel', 'deny', 'expire' => 'failed',
            default                => $trx->status,
        };

        // Update transaction
        $trx->update([
            'status' => in_array($status, ['failed']) ? 'cancelled' : $status, // karena enum transaksi kamu belum punya 'failed'
            'paid_amount' => $status === 'paid' ? $trx->total : $trx->paid_amount,
            'change_amount' => 0,
        ]);

        // Update payment
        Payment::updateOrCreate(
            ['transaction_id' => $trx->id],
            [
                'amount' => $trx->total,
                'status' => $status,
                'payment_method' => $paymentType, // untuk qris biasanya "qris"
                'payment_date' => $status === 'paid' ? now() : null,
            ]
        );
    }
}
