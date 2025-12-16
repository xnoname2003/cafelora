<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Services\MidtransPaymentService;

class MidtransWebhookController extends Controller
{
    public function notify(Request $request, MidtransPaymentService $service)
    {
        $payload = $request->all();

        $trx = Transaction::where('invoice', $payload['order_id'] ?? null)->firstOrFail();

        $service->apply($trx, $payload);

        return response()->json(['message' => 'OK']);
    }
}
