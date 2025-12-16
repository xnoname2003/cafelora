<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column: Order Details -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Informasi Order</h2>

            <div class="space-y-2 text-gray-600 dark:text-white">
                {{-- @dd($transaction); --}}
                <p><span class="font-medium">Nomor Invoice:</span> {{ $transaction->invoice }}</p>
                <p><span class="font-medium">Nomor Antrian:</span> <span
                        class="text-lg font-bold">#{{ $transaction->queue_number }}</span></p>
                <p><span class="font-medium">Nama Pelanggan:</span> {{ $transaction->user->name ?? 'Guest' }}</p>
                <p><span class="font-medium">Metode Pembayaran:</span>
                    {{ ucfirst($transaction->payments->last()?->payment_method ?? '-') }}</p>
                <div class="flex items-center gap-2">
                    <span class="font-medium">Status:</span>
                    <x-filament::badge :color="match ($transaction->status) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }">
                        {{ ucfirst($transaction->status) }}
                    </x-filament::badge>
                </div>
                <p><span class="font-medium">Total:</span> Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                <p><span class="font-medium">Tanggal:</span> {{ $transaction->created_at->format('d M Y H:i') }}</p>
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-800 dark:text-white">Item Order</h3>
            <div class="overflow-x-auto border border-gray-200 dark:text-white dark:border-gray-700 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-white">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->items as $detail)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white align-top">
                                    <div class="font-bold text-base">{{ $detail->menu->name ?? 'Item dihapus' }}</div>

                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 space-y-1">
                                        {{-- Base Price --}}
                                        <div
                                            class="flex justify-between gap-4 border-b border-dashed border-gray-300 dark:border-gray-600 pb-1 mb-1">
                                            <span>Base</span>
                                            <span>Rp
                                                {{ number_format($detail->menu->base_price ?? 0, 0, ',', '.') }}</span>
                                        </div>

                                        {{-- Varian --}}
                                        @if ($detail->variant)
                                            <div class="flex justify-between gap-4">
                                                <span>Varian: {{ $detail->variant->name }}</span>
                                                <span>Rp
                                                    {{ number_format($detail->variant->price ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        @endif

                                        {{-- Toppings --}}
                                        @if ($detail->toppings && $detail->toppings->count() > 0)
                                            @foreach ($detail->toppings as $topping)
                                                <div class="flex justify-between gap-4">
                                                    <span>+ {{ $topping->topping->name }}</span>
                                                    <span>Rp {{ number_format($topping->price, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $detail->quantity }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Payment -->
        <div class="flex flex-col gap-4">
            @if ($transaction->status != 'paid' && isset($snapToken))
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Metode Pembayaran</h3>

                    {{-- Pilihan Pembayaran --}}
                    <div class="flex flex-wrap gap-3 mb-6">
                        <button wire:click="$set('paymentMethod', 'cash')"
                            class="px-4 py-2 text-sm rounded-full transition-all duration-200 border 
                                    @if ($paymentMethod === 'cash') bg-primary-600 text-white border-primary-600 
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border-transparent hover:bg-gray-200 dark:hover:bg-gray-600 @endif">
                            Cash
                        </button>
                        <button wire:click="$set('paymentMethod', 'other')"
                            class="px-4 py-2 text-sm rounded-full transition-all duration-200 border
                                    @if ($paymentMethod === 'other') bg-primary-600 text-white border-primary-600 
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border-transparent hover:bg-gray-200 dark:hover:bg-gray-600 @endif">
                            Other (QRIS / Transfer)
                        </button>
                    </div>

                    @if ($paymentMethod === 'cash')
                        <form wire:submit="submitCash">
                            {{ $this->form }}

                            <div class="mt-4">
                                <x-filament::button type="submit" class="w-full" size="lg">
                                    Bayar Cash
                                </x-filament::button>
                            </div>
                        </form>
                    @else
                        <div class="flex flex-col items-center justify-center text-center pt-4">
                            <p class="text-base font-medium mb-4 text-gray-700 dark:text-gray-200">Lanjutkan pembayaran
                                via QRIS atau transfer</p>

                            <x-filament::button type="button" size="xl" class="w-full" x-data
                                x-on:click="window.snap.pay('{{ $snapToken }}', {
                                onSuccess: (result) => { $wire.handleMidtransSuccess(result) },
                                onPending: (result) => { console.log(result) },
                                onError: (result) => { console.log(result) },
                            })">
                                Bayar Sekarang
                            </x-filament::button>

                        </div>
                    @endif
                </div>
            @elseif($transaction->status == 'paid')
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <x-heroicon-o-check-circle class="w-16 h-16 text-green-500 mb-4" />
                    <p class="text-xl font-bold text-green-700 dark:text-green-400">Pembayaran Berhasil</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Transaksi ini sudah lunas.</p>
                </div>
            @endif
        </div>
    </div>

    @if (isset($snapToken))
        @once
            <script
                src="{{ config('midtrans.is_production')
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
                data-client-key="{{ config('midtrans.client_key') }}"></script>
        @endonce
    @endif
</x-filament-panels::page>
