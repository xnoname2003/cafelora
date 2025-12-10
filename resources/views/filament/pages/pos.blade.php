<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
        {{-- Keranjang --}}
        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-bold mb-4">Keranjang</h2>

            <form wire:submit.prevent="submit" class="space-y-4">
                {{ $this->form }}

                <div class="flex justify-end mt-4">
                    <x-filament::button type="submit" color="success">
                        Finalisasi & Bayar
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- Menu --}}
        <div class="p-4 bg-white shadow rounded">
            <h2 class="font-bold mb-4">Menu</h2>

            {{-- Filter kategori --}}
            <div class="flex gap-2 mb-4">
                <button wire:click="$set('selectedCategory', null)"
                        class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
                    Semua
                </button>
                @foreach(\App\Models\Category::all() as $category)
                    <button wire:click="$set('selectedCategory', {{ $category->id }})"
                            class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- Grid menu --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($this->menus as $menu)
                    <div class="bg-white rounded-xl shadow-md border p-4 flex flex-col h-full">

                        {{-- Gambar Menu --}}
                        @if ($menu->image)
                            <img src="{{ $menu->image }}"
                                 alt="{{ $menu->name }}"
                                 class="rounded-lg h-32 w-full object-cover mb-4">
                        @else
                            <div class="bg-gray-100 rounded-lg h-32 flex items-center justify-center mb-4">
                                <span class="text-gray-400 text-sm">No Image</span>
                            </div>
                        @endif

                        {{-- Nama Menu --}}
                        <h3 class="font-bold text-lg text-gray-800">{{ $menu->name }}</h3>

                        {{-- Kategori --}}
                        <p class="text-xs text-gray-500 italic mb-2">
                            {{ $menu->category->name ?? 'Kategori' }}
                        </p>

                        {{-- Harga & Stok --}}
                        <div class="text-sm text-gray-700 mb-3">
                            <strong>Harga:</strong> Rp {{ number_format($menu->base_price, 0, ',', '.') }}<br>
                            <strong>Stok:</strong> {{ $menu->stock ?? '-' }}
                        </div>

                        {{-- Varian --}}
                        @if ($menu->variants->count())
                            <label class="block text-xs font-semibold">Varian:</label>
                            <select
                                wire:model.defer="selectedVariant.{{ $menu->id }}"
                                class="w-full mt-1 p-2 border rounded text-sm">
                                <option value="">Pilih Varian</option>
                                @foreach ($menu->variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->name }}
                                        @if($variant->price_adjustment > 0)
                                            (+{{ number_format($variant->price_adjustment, 0, ',', '.') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        @endif

                        {{-- Qty --}}
                        <label class="block text-xs font-semibold mt-3">Qty:</label>
                        <input type="number"
                               wire:model.defer="selectedQty.{{ $menu->id }}"
                               min="1"
                               class="w-full mt-1 p-2 border rounded text-sm"
                               value="1">

                        {{-- Topping --}}
                        @if ($menu->toppings->count())
                            <label class="block text-xs font-semibold mt-3">Topping:</label>
                            <div class="text-sm mt-1 space-y-1">
                                @foreach ($menu->toppings as $topping)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox"
                                               wire:model.defer="selectedToppings.{{ $menu->id }}"
                                               value="{{ $topping->id }}"
                                               class="rounded">
                                        {{ $topping->name }}
                                        <span class="text-gray-500">(+{{ number_format($topping->price, 0, ',', '.') }})</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                        
                        {{-- Tombol Tambah ke Keranjang --}}
                        <div class="mt-auto">
                            <x-filament::button
                                wire:click="addToCart({{ $menu->id }})"
                                class="w-full mt-4">
                                Tambah ke Keranjang
                            </x-filament::button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>