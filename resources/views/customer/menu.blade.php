<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pembeli Estetik</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soft-brown': {
                            100: '#FAF0E6', 
                            200: '#EAD7BC', 
                            500: '#A0522D', 
                            700: '#5D4037', 
                        },
                    }
                }
            }
        }
    </script>

    <style>
        .card:hover {
            transform: scale(1.02); 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); 
    </style>
</head>

<body class="bg-soft-brown-200 p-8 min-h-screen">

    <header class="max-w-7xl mx-auto mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold text-center text-soft-brown-700 tracking-tight">
            ☕ Katalog Menu 🍰
        </h1>
        <p class="text-center text-soft-brown-500 mt-3 text-lg font-light">
            Silakan lihat menu lezat kami. Pemesanan akan diproses oleh kasir.
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">

        @foreach ($menus as $menu)
        <div class="card bg-soft-brown-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-500 ease-in-out overflow-hidden transform cursor-pointer">

            <div class="relative h-56 w-full">
                <img
                    src="{{ asset('storage/' . $menu->image) }}"
                    alt="Gambar {{ $menu->name }}"
                    class="w-full h-full object-cover"
                >
                <span class="absolute top-3 right-3 bg-soft-brown-700 text-soft-brown-100 px-3 py-1 text-xs font-semibold rounded-full shadow-md">
                    {{ $menu->category->name }}
                </span>
            </div>

            <div class="p-6">
                <h3 class="text-2xl font-bold mb-1 text-soft-brown-700">
                    {{ $menu->name }}
                </h3>

                <p class="mt-2 text-soft-brown-500 text-sm h-12 overflow-hidden">
                    {{ $menu->description ?? 'Deskripsi tidak tersedia.' }}
                </p>
                
                <hr class="my-4 border-soft-brown-200">

                <div class="flex justify-between items-center mb-4">
                    <div class="flex flex-col">
                        <span class="text-soft-brown-500 text-xs uppercase font-medium">Harga</span>
                        <p class="text-2xl font-extrabold text-soft-brown-500">
                            Rp {{ number_format($menu->base_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium {{ $menu->stock > 5 ? 'text-green-600' : 'text-red-500' }}">
                            Stok: {{ $menu->stock }}
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    @if ($menu->variants->isNotEmpty())
                    <div>
                        <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Pilihan Varian:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($menu->variants as $variant)
                                <span class="px-3 py-1 bg-soft-brown-100 text-soft-brown-500 border border-soft-brown-500 rounded-full text-xs font-medium shadow-sm">
                                    {{ $variant->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if ($menu->toppings->isNotEmpty())
                    <div>
                        <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Topping Tersedia:</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($menu->toppings as $topping)
                                <span class="px-3 py-1 bg-soft-brown-500 text-soft-brown-100 rounded-full text-xs font-medium shadow-sm">
                                    {{ $topping->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-6 text-center pt-3 border-t border-soft-brown-200">
                    <span class="text-xs text-soft-brown-200 select-none italic">
                        🚫 Hanya Tampilan Menu
                    </span>
                </div>

            </div>
        </div>
        @endforeach

    </div>

    <footer class="mt-16 text-center text-soft-brown-500 text-sm">
        <p>&copy; {{ date('Y') }} Sistem {{ config('app.name', 'Restoran') }}. Dibuat dengan Laravel & Filament.</p>
    </footer>

</body>
</html>