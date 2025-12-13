<div class="ticket">
    <div class="center">
        <strong>CafeLora</strong><br>
        {{ $transaction->invoice }}<br>
        {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        Kasir: {{ $transaction->user?->name ?? '-' }}
    </div>

    <div class="line"></div>

    @foreach($transaction->items as $item)
        <div class="item-name">{{ $item->menu->name }}</div>
        @if($item->variant)
            <div class="item-meta">Varian: {{ $item->variant->name }}</div>
        @endif
        @if($item->toppings && $item->toppings->count())
            <div class="item-meta">Topping: {{ $item->toppings->pluck('topping.name')->join(', ') }}</div>
        @endif
        <div class="row">
            <div>x{{ $item->quantity }} @ Rp {{ number_format($item->price,0,',','.') }}</div>
            <div>Rp {{ number_format($item->subtotal,0,',','.') }}</div>
        </div>
    @endforeach

    <div class="line"></div>

    <div class="row"><strong>Total</strong><strong>Rp {{ number_format($transaction->total,0,',','.') }}</strong></div>
    <div class="row"><span>Bayar</span><span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span></div>
    <div class="row"><span>Kembalian</span><span>Rp {{ number_format($transaction->change_amount,0,',','.') }}</span></div>

    <div class="line"></div>
    <div class="center footer">Terima kasih atas kunjungan Anda!</div>
</div>

<style>
    .ticket { width: 302px; padding: 8px 10px; font-family: monospace; }
    .center { text-align: center; }
    .line { border-top: 1px dashed #000; margin: 6px 0; }
    .row { display: flex; justify-content: space-between; font-size: 12px; }
    .item-name { font-size: 12px; font-weight: bold; }
    .item-meta { font-size: 11px; color: #444; }
    .footer { margin-top: 10px; font-size: 11px; }
</style>