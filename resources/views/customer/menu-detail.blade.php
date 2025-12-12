<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Menu: {{ $menu->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'soft-brown': {
                            100: '#FAF0E6', 
                            200: '#EAD7BC', 
                            300: '#C9A380', 
                            500: '#A0522D', 
                            700: '#5D4037', 
                        },
                        'olive-dark': '#5D4037',
                    }
                }
            }
        }
    </script>
    <style>
        .price-chip {
            background-color: #A0522D; /* soft-brown-500 */
            color: #FAF0E6; /* soft-brown-100 */
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.75rem; /* text-xs */
            margin-left: 0.5rem;
        }
    </style>
</head>
<body class="bg-soft-brown-200 min-h-screen">

    {{-- Navigasi --}}
    <nav class="w-full bg-soft-brown-300 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center py-4 px-8"> 
            <a href="{{ route('customer.menu.index') }}" class="text-xl font-bold text-soft-brown-100 hover:text-white transition">
                &larr; Kembali ke Daftar Menu
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="bg-soft-brown-100 rounded-xl shadow-2xl overflow-hidden p-8 md:p-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16">
                
                {{-- Bagian Kiri: Gambar --}}
                <div>
                    @if ($menu->image)
                    <img 
                        src="{{ $menu->image }}" 
                        alt="Gambar {{ $menu->name }}" 
                        class="w-full h-96 object-cover rounded-xl shadow-lg border-4 border-soft-brown-300"
                    >
                    @else
                    <div class="w-full h-96 flex items-center justify-center rounded-xl shadow-lg border-4 border-soft-brown-300 bg-soft-brown-200 text-soft-brown-700 font-bold text-xl">
                        Gambar Tidak Tersedia
                    </div>
                    @endif
                </div>

                {{-- Bagian Kanan: Detail --}}
                <div>
                    <span class="inline-block px-4 py-1 mb-3 text-sm font-semibold rounded-full bg-soft-brown-300 text-soft-brown-700">
                        {{ $menu->category->name ?? 'Tanpa Kategori' }}
                    </span>
                    
                    <h1 class="text-4xl md:text-5xl font-extrabold text-soft-brown-700 mb-4 leading-tight">
                        {{ $menu->name }}
                    </h1>
                    
                    <p class="text-3xl font-bold text-soft-brown-500 mb-8">
                        Rp {{ number_format($menu->base_price, 0, ',', '.') }}
                    </p>

                    <div class="mb-10 border-t border-b border-soft-brown-300 py-6">
                        <h2 class="text-xl font-bold text-soft-brown-700 mb-3">Deskripsi Produk</h2>
                        <p class="text-soft-brown-500 text-base leading-relaxed">
                            {{ $menu->description ?? 'Deskripsi detail menu ini belum tersedia.' }}
                        </p>
                    </div>

                    <div class="space-y-6">
                        
                        {{-- Varian (dengan harga extra_price) --}}
                        <div>
                            <h3 class="text-lg font-bold text-soft-brown-700 mb-2">Pilihan Varian:</h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse ($menu->variants as $variant)
                                    <span class="inline-flex items-center px-4 py-2 bg-soft-brown-200 text-soft-brown-700 border border-soft-brown-500 rounded-full text-sm font-medium shadow-sm">
                                        {{ $variant->name }} 
                                        {{-- Tampilkan harga jika ada --}}
                                        @if (isset($variant->price) && $variant->price > 0) 
                                            <span class="price-chip">+Rp{{ number_format($variant->price, 0, ',', '.') }}</span>
                                        @elseif (isset($variant->extra_price) && $variant->extra_price > 0) 
                                            {{-- Menggunakan variable yang Anda berikan: extra_price --}}
                                            <span class="price-chip">+Rp{{ number_format($variant->extra_price, 0, ',', '.') }}</span>
                                        @endif
                                    </span>
                                @empty
                                    <span class="text-soft-brown-500 italic">Tidak ada varian yang tersedia.</span>
                                @endforelse
                            </div>
                        </div>
                        
                        {{-- Topping (dengan harga extra_price) --}}
                        <div>
                            <h3 class="text-lg font-bold text-soft-brown-700 mb-2">Topping Tersedia:</h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse ($menu->toppings as $topping)
                                    <span class="inline-flex items-center px-4 py-2 bg-soft-brown-500 text-soft-brown-100 rounded-full text-sm font-medium shadow-sm">
                                        {{ $topping->name }} 
                                        {{-- Tampilkan harga jika ada --}}
                                        @if (isset($topping->price) && $topping->price > 0)
                                            <span class="price-chip bg-soft-brown-700">+Rp{{ number_format($topping->price, 0, ',', '.') }}</span>
                                        @elseif (isset($topping->extra_price) && $topping->extra_price > 0) 
                                            {{-- Menggunakan variable yang Anda berikan: extra_price --}}
                                            <span class="price-chip bg-soft-brown-700">+Rp{{ number_format($topping->extra_price, 0, ',', '.') }}</span>
                                        @endif
                                    </span>
                                @empty
                                    <span class="text-soft-brown-500 italic">Tidak ada topping yang tersedia.</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="pt-6 border-t border-soft-brown-300">
                            <p class="text-soft-brown-700 font-semibold">
                                Total Terjual: <span class="text-xl font-extrabold ml-1">{{ $menu->sales_qty ?? 0 }}x</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>

    <footer class="mt-16 text-center text-soft-brown-500 text-sm pb-8">
        <p>&copy; {{ date('Y') }} Sistem Cafelora.</p>
    </footer>
</body>
</html>