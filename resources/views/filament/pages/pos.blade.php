<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-6 w-full max-w-screen-xl mx-auto items-start">

        {{-- KERANJANG --}}
        <div
            class="hidden lg:block lg:w-[400px] flex-none bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 sticky top-6">
            <h2 class="text-2xl font-extrabold text-primary-600 mb-4 border-b dark:border-gray-700 pb-2">Keranjang
                Belanja</h2>

            <div class="overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-3 space-y-4"
                style="max-height: calc(100vh - 150px); width:275px;">

                <form wire:submit.prevent="submit" class="space-y-4" id="submit-cart">
                    {{ $this->form }}
                </form>
            </div>
            <div class="flex justify-end mt-4 pt-4 border-t dark:border-gray-700">
                <x-filament::button wire:click="submit" color="success" class="text-lg py-2 px-6">
                    Finalisasi & Bayar
                </x-filament::button>
            </div>
        </div>

        {{-- MENU --}}
        <div class="flex-1 w-full min-w-0 bg-white dark:bg-gray-800 shadow-xl dark:shadow-2xl rounded-lg p-6">
            <h2 class="text-2xl font-extrabold text-primary-600 mb-4 border-b dark:border-gray-700 pb-2">Daftar Menu
            </h2>

            {{-- Filter kategori --}}
            <div
                class="flex flex-wrap gap-3 mb-4 bg-white dark:bg-gray-800 z-10 py-3 -m-3 border-b dark:border-gray-700 shadow-sm">
                <button wire:click="$set('selectedCategory', null)"
                    class="px-4 py-1 text-sm rounded-full transition-all duration-200 
                                @if (is_null($selectedCategory)) bg-primary-600 text-white @else bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-500/20 text-gray-700 dark:text-gray-200 @endif">
                    Semua
                </button>

                <button wire:click="$set('selectedCategory', -1)"
                    class="px-4 py-1 text-sm rounded-full transition-all duration-200 
                            @if ($selectedCategory === -1) bg-primary-600 text-white 
                            @else bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-500/20 text-gray-700 dark:text-gray-200 @endif">
                    Best Seller
                </button>

                @foreach (\App\Models\Category::all() as $category)
                    <button wire:click="$set('selectedCategory', {{ $category->id }})"
                        class="px-4 py-1 text-sm rounded-full transition-all duration-200 
                                @if ($selectedCategory == $category->id) bg-primary-600 text-white @else bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-500/20 text-gray-700 dark:text-gray-200 @endif">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <div class="overflow-y-auto" style="max-height: calc(100vh - 120px);">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 pb-4">
                    @foreach ($this->menus as $menu)
                        <div
                            class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 transition duration-300 hover:shadow-2xl flex flex-col h-full">

                            {{-- Gambar Menu --}}
                            @if ($menu->image)
                                <x-menu-image :menu="$menu" class="rounded-t-xl h-40 w-full object-cover" />
                            @else
                                <div
                                    class="bg-gray-100 dark:bg-gray-700 rounded-t-xl h-40 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm italic">Gambar Tidak Tersedia</span>
                                </div>
                            @endif

                            <div class="p-4 flex flex-col flex-grow">
                                {{-- Nama Menu dan Kategori --}}
                                <h3 class="font-bold text-xl text-gray-900 dark:text-white leading-snug">
                                    {{ $menu->name }}</h3>
                                <p class="text-xs text-primary-500 font-semibold mb-3">
                                    {{ $menu->category->name ?? 'Kategori' }}</p>

                                {{-- Harga, Stok, Terjual, Varian, Topping --}}
                                <div class="text-base text-gray-700 dark:text-gray-200 mb-4 flex-grow">
                                    {{-- Harga --}}
                                    <span class="font-bold">
                                        Rp {{ number_format($menu->base_price, 0, ',', '.') }}
                                    </span>

                                    {{-- Status Stok --}}
                                    @if (($menu->stock ?? 0) <= 0)
                                        <span class="text-sm font-bold text-red-600 block mt-1">Stok Habis!</span>
                                    @else
                                        <span class="text-xs font-normal text-gray-500 block mt-1">Stok:
                                            {{ $menu->stock ?? '-' }}</span>
                                    @endif

                                    {{-- Terjual --}}
                                    <span class="text-xs font-normal text-green-600 block mt-1">
                                        Terjual: {{ $menu->sales_qty ?? 0 }}
                                    </span>

                                    {{-- Variant dan Topping --}}
                                    @if ($menu->variants->count() || $menu->toppings->count())
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            @if ($menu->variants->count())
                                                <div class="grid grid-cols-2 gap-x-2">
                                                    <span class="font-bold">Varian:</span>
                                                    <span
                                                        class="font-normal">{{ $menu->variants->pluck('name')->join(', ') }}</span>
                                                </div>
                                            @endif
                                            @if ($menu->toppings->count())
                                                <div class="grid grid-cols-2 gap-x-2">
                                                    <span class="font-bold">Topping:</span>
                                                    <span
                                                        class="font-normal">{{ $menu->toppings->pluck('name')->join(', ') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Tombol Tambah Ke Keranjang --}}
                                <div class="mt-auto">
                                    @if (($menu->stock ?? 0) <= 0)
                                        <x-filament::button {{-- Penyesuaian Dark Mode di sini --}}
                                            class="w-full bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-400 cursor-not-allowed"
                                            disabled>
                                            Stok Habis
                                        </x-filament::button>
                                    @else
                                        <x-filament::button wire:click="addToCart({{ $menu->id }})" class="w-full"
                                            color="primary">
                                            + Tambah
                                        </x-filament::button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Icon Mobile --}}
    <button wire:click="$toggle('showCart')"
        class="fixed bottom-4 right-4 bg-primary-600 text-white p-4 rounded-full shadow-lg lg:hidden">
        <x-heroicon-o-shopping-cart class="w-6 h-6" />
    </button>

    {{-- Keranjang Mobile --}}
    @if ($showCart)
        <div class="fixed inset-0 bg-black/50 flex justify-center items-end lg:hidden z-50">
            <div class="bg-white dark:bg-gray-800 w-full rounded-t-xl p-4 max-h-[90vh] overflow-y-auto relative">

                {{-- Tombol Kembali --}}
                <button wire:click="$set('showCart', false)"
                    class="absolute top-3 right-3 z-10 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>

                <h2 class="text-xl font-bold mb-4 pt-2 text-center border-b dark:border-gray-700 pb-2">Keranjang Belanja
                </h2>

                <div class="overflow-y-auto px-4" style="max-height: calc(90vh - 120px);">
                    <form wire:submit.prevent="submit" id="submit-cart-mobile" class="space-y-4">
                        {{ $this->form }}

                        {{-- Bayar --}}
                        <div class="sticky bottom-0 bg-white dark:bg-gray-800 pt-4 border-t dark:border-gray-700">
                            <div class="flex justify-end">
                                <x-filament::button type="submit" color="success" class="w-full">
                                    Finalisasi & Bayar
                                </x-filament::button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Preview Struk --}}
    <x-filament::modal id="receipt-preview">
        @if ($transaction)
            @include('receipt._partial', ['transaction' => $transaction])
            <div class="flex justify-end gap-2 mt-4">
                <x-filament::button onclick="window.print()" color="primary">
                    Print
                </x-filament::button>
                <x-filament::button wire:click="$dispatch('close-modal', { id: 'receipt-preview' })" color="danger">
                    Tutup
                </x-filament::button>
            </div>
        @endif
    </x-filament::modal>



</x-filament-panels::page>
