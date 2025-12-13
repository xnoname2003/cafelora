<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk {{ $transaction->invoice }}</title>
    <style>
        body { font-family: monospace; margin: 0; padding: 0; }
        .ticket { width: 302px; padding: 8px 10px; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; font-size: 12px; }
        .item-name { font-size: 12px; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 11px; }
    </style>
</head>
<body onload="window.print()">
<div class="ticket">

    {{-- Header --}}
    <div class="center">
        <strong>CafeLora</strong><br>
        {{ $transaction->invoice }}<br>
        {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        Kasir: {{ $transaction->user?->name ?? '-' }}
    </div>

    <div class="line"></div>

    {{-- Items --}}
    @foreach($transaction->items as $item)
        <div class="item-name">{{ $item->menu->name }}</div>

        {{-- Base price --}}
        <div class="row">
            <span>Base</span>
            <span>Rp {{ number_format($item->menu->base_price,0,',','.') }}</span>
        </div>

        {{-- Varian --}}
        @if($item->variant)
            <div class="row">
                <span>Varian: {{ $item->variant->name }}</span>
                <span>Rp {{ number_format($item->variant->price ?? 0,0,',','.') }}</span>
            </div>
        @endif

        {{-- Topping --}}
        @if($item->toppings && $item->toppings->count())
            @foreach($item->toppings as $topping)
                <div class="row">
                    <span>Topping: {{ $topping->topping->name }}</span>
                    <span>Rp {{ number_format($topping->price ?? 0,0,',','.') }}</span>
                </div>
            @endforeach
        @endif

        {{-- Qty & Subtotal --}}
        <div class="row">
            <span>x{{ $item->quantity }}</span>
            <span>Rp {{ number_format($item->subtotal,0,',','.') }}</span>
        </div>

        <div class="line"></div>
    @endforeach

    {{-- Totals --}}
    <div class="row"><strong>Total</strong><strong>Rp {{ number_format($transaction->total,0,',','.') }}</strong></div>
    <div class="row"><span>Bayar</span><span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span></div>
    <div class="row"><span>Kembalian</span><span>Rp {{ number_format($transaction->change_amount,0,',','.') }}</span></div>

    <div class="line"></div>

    {{-- Footer --}}
    <div class="center footer">Terima kasih atas kunjungan Anda!</div>
</div>
</body>
</html>