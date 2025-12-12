<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Menu Cafelora</title>

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
        .card {
            transition: transform 0.5s ease-in-out, box-shadow 0.5s ease-in-out; 
            cursor: pointer; 
        }
        .card:hover {
            transform: scale(1.02); 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .menu-header-effect {
            text-align: center;
            position: relative;
            z-index: 10;
        }
        .menu-header-effect::before {
            content: 'OUR MENU'; 
            position: absolute;
            top: 50%;
            left: 50%;
            font-family: sans-serif;
            font-size: 8rem; 
            font-weight: 800; 
            line-height: 1;
            transform: translate(-50%, -50%);
            color: transparent; 
            -webkit-text-stroke: 1px #5D4037; 
            opacity: 0.8; 
            z-index: -1; 
            letter-spacing: 0.5rem;
            white-space: nowrap; 
        }
        
        .menu-header-main-text {
            font-size: 3.5rem; 
            font-weight: 800;
            color: #5D4037; 
            line-height: 1;
        }
        
        .fixed-title {
            height: 60px; 
            overflow: hidden;
        }

        .fixed-description {
            height: 48px; 
            overflow: hidden;
        }
        
        .fixed-option-area {
            min-height: 120px; 
        }
    </style>
</head>

<body class="bg-soft-brown-200 min-h-screen">

    <nav class="w-full bg-soft-brown-300 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center py-4 px-8 space-y-3 md:space-y-0"> 
            
            <div class="flex items-center space-x-2">
                <span class="text-3xl font-extrabold text-soft-brown-100">Cafelora</span>
            </div>

            <form action="{{ route('customer.menu.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full md:max-w-4xl justify-end">
                
                <div class="relative w-full md:w-1/3">
                    <input 
                        type="text" 
                        placeholder="Cari nama menu..." 
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 transition duration-150 text-soft-brown-700 bg-soft-brown-100"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-5 w-5 text-soft-brown-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <input 
                    type="number" 
                    placeholder="Harga Min" 
                    name="min_price"
                    value="{{ request('min_price') }}"
                    min="0"
                    class="w-full md:w-1/6 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100"
                >

                <input 
                    type="number" 
                    placeholder="Harga Max" 
                    name="max_price"
                    value="{{ request('max_price') }}"
                    min="0"
                    class="w-full md:w-1/6 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100"
                >
                
                <select 
                    name="category"
                    class="w-full md:w-1/5 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100"
                >
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($allCategories as $category)
                        <option 
                            value="{{ $category->name }}"
                            {{ request('category') == $category->name ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button 
                    type="submit" 
                    class="w-full md:w-auto px-4 py-2 bg-soft-brown-700 text-soft-brown-100 rounded-lg font-semibold hover:bg-olive-dark transition duration-150"
                >
                    Cari
                </button>
                
                @if (isset($isSearching) && $isSearching)
                    <a href="{{ route('customer.menu.index') }}" class="w-full md:w-auto px-4 py-2 bg-red-600 text-soft-brown-100 rounded-lg font-semibold hover:bg-red-700 text-center transition duration-150">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </nav>
    
    <div class="pt-8"> 
        
        <header class="max-w-7xl mx-auto mb-12 px-8">
            <div class="relative w-full h-72 rounded-xl overflow-hidden shadow-xl">
                
                <img 
                    src="https://media.istockphoto.com/id/498240015/id/foto/biji-kopi.jpg?s=612x612&w=0&k=20&c=zuDf8jep5NSbAIn9TM6qCCCetJbSMc4GKn33mUigTr8="
                    class="absolute inset-0 w-full h-full object-cover object-center opacity-100" 
                    alt="Latar Belakang Menu Kopi"
                >

                <div class="absolute inset-0 bg-soft-brown-100 opacity-40"></div>

                <div class="absolute inset-0 flex items-center justify-center menu-header-effect">
                    <h1 class="menu-header-main-text">MENU</h1>
                </div>
            </div>
        </header>
        
        <div class="max-w-7xl mx-auto space-y-20 px-8">

            @php
                $firstMenuId = null;

                if (isset($isSearching) && $isSearching && $filteredMenus->isNotEmpty()) {
                    $firstMenuId = $filteredMenus->first()->id;
                }
                
                $isCategoryOnlySearch = 
                    request('category') && 
                    !request('search') && 
                    !request('min_price') && 
                    !request('max_price');
                
                $searchTitle = "Hasil Pencarian";
                if ($isCategoryOnlySearch) {
                    $searchTitle = "Hasil Kategori: " . request('category');
                } elseif (request('search')) {
                    $searchTitle = "Hasil Pencarian: \"" . request('search') . "\"";
                }
                
                if (isset($isSearching) && $isSearching) {
                    $groupedMenus = $filteredMenus->groupBy(function($menu) {
                        return $menu->category->name ?? 'Lainnya'; 
                    });
                }
            @endphp

            @if (isset($isSearching) && $isSearching)
                <section>
                    <h2 class="text-3xl font-extrabold text-soft-brown-700 mb-6 uppercase tracking-wide">
                        {{ $searchTitle }}
                    </h2>
                    
                    @if ($filteredMenus->isEmpty())
                        <p class="text-lg text-soft-brown-500 italic">Menu tidak ditemukan dengan filter yang diterapkan.</p>
                    @else
                        @foreach ($groupedMenus as $categoryName => $menus)
                            <h3 class="text-2xl font-bold text-soft-brown-700 mb-4 mt-8">{{ $categoryName }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-10">
                                @foreach ($menus as $menu)
                                    @php
                                        $menuId = $menu->id; 
                                        $salesQty = $menu->sales_qty ?? 0;
                                        $salesColor = ($salesQty < 10) ? 'text-red-600' : (($salesQty < 20) ? 'text-soft-brown-500' : (($salesQty < 30) ? 'text-yellow-600' : 'text-green-600'));
                                        
                                        $detailUrl = route('customer.menu.show', ['id' => $menu->id]);
                                    @endphp
                                    
                                    <a 
                                        href="{{ $detailUrl }}" 
                                        class="card bg-soft-brown-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-500 ease-in-out overflow-hidden transform" 
                                        id="menu-{{ $menuId }}"
                                    >
                                        <div class="relative h-56 w-full">
                                            <img src="{{ $menu->image }}" alt="Gambar {{ $menu->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-6">
                                            <h3 class="text-2xl font-bold mb-1 text-soft-brown-700 fixed-title">{{ $menu->name }}</h3>
                                            
                                            <p class="mt-2 text-soft-brown-500 text-sm fixed-description">{{ $menu->description ?? 'Deskripsi tidak tersedia.' }}</p>
                                            
                                            <hr class="my-4 border-soft-brown-200">
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex flex-col">
                                                    <span class="text-soft-brown-500 text-xs uppercase font-medium">Harga</span>
                                                    <p class="text-2xl font-extrabold text-soft-brown-500">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-soft-brown-500 text-xs uppercase font-medium block">Terjual</span>
                                                    <p class="text-lg font-bold {{ $salesColor }}">{{ $salesQty }}x</p>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3 fixed-option-area">
                                                <div>
                                                    <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Pilihan Varian:</span>
                                                    <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                        @if ($menu->variants->isNotEmpty())
                                                            @foreach ($menu->variants as $variant)
                                                                <span class="px-3 py-1 bg-soft-brown-100 text-soft-brown-500 border border-soft-brown-500 rounded-full text-xs font-medium shadow-sm">
                                                                    {{ $variant->name }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-soft-brown-400 text-xs italic">Tidak ada varian</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Topping Tersedia:</span>
                                                    <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                        @if ($menu->toppings->isNotEmpty())
                                                            @foreach ($menu->toppings as $topping)
                                                                <span class="px-3 py-1 bg-soft-brown-500 text-soft-brown-100 rounded-full text-xs font-medium shadow-sm">
                                                                    {{ $topping->name }}
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-soft-brown-400 text-xs italic">Tidak ada topping</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </a> 
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </section>
                
            @else
                @foreach ($categories as $category)
                <section>
                    <h2 class="text-3xl font-extrabold text-soft-brown-700 mb-6 uppercase tracking-wide">
                        {{ $category->name }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach ($category->menus as $menu)
                            @php
                                $menuId = $menu->id; 
                                $salesQty = $menu->sales_qty ?? 0;
                                $salesColor = ($salesQty < 10) ? 'text-red-600' : (($salesQty < 20) ? 'text-soft-brown-500' : (($salesQty < 30) ? 'text-yellow-600' : 'text-green-600'));

                                $detailUrl = route('customer.menu.show', ['id' => $menu->id]);
                            @endphp
                            
                            <a 
                                href="{{ $detailUrl }}" 
                                class="card bg-soft-brown-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-500 ease-in-out overflow-hidden transform" 
                                id="menu-{{ $menuId }}"
                            >
                                <div class="relative h-56 w-full">
                                    <img src="{{ $menu->image }}" alt="Gambar {{ $menu->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6">
                                    <h3 class="text-2xl font-bold mb-1 text-soft-brown-700 fixed-title">{{ $menu->name }}</h3>
                                    
                                    <p class="mt-2 text-soft-brown-500 text-sm fixed-description">{{ $menu->description ?? 'Deskripsi tidak tersedia.' }}</p>
                                    
                                    <hr class="my-4 border-soft-brown-200">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex flex-col">
                                            <span class="text-soft-brown-500 text-xs uppercase font-medium">Harga</span>
                                            <p class="text-2xl font-extrabold text-soft-brown-500">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-soft-brown-500 text-xs uppercase font-medium block">Terjual</span>
                                            <p class="text-lg font-bold {{ $salesColor }}">{{ $salesQty }}x</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 fixed-option-area">
                                        <div>
                                            <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Pilihan Varian:</span>
                                            <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                @if ($menu->variants->isNotEmpty())
                                                    @foreach ($menu->variants as $variant)
                                                        <span class="px-3 py-1 bg-soft-brown-100 text-soft-brown-500 border border-soft-brown-500 rounded-full text-xs font-medium shadow-sm">
                                                            {{ $variant->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-soft-brown-400 text-xs italic">Tidak ada varian</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Topping Tersedia:</span>
                                            <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                @if ($menu->toppings->isNotEmpty())
                                                    @foreach ($menu->toppings as $topping)
                                                        <span class="px-3 py-1 bg-soft-brown-500 text-soft-brown-100 rounded-full text-sm font-medium shadow-sm">
                                                            {{ $topping->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-soft-brown-400 text-xs italic">Tidak ada topping</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </a> 
                        @endforeach
                    </div>
                </section>
                @endforeach
            @endif

        </div>
    </div>

    <footer class="mt-16 text-center text-soft-brown-500 text-sm pb-8">
        <p>&copy; {{ date('Y') }} Sistem Cafelora. Dibuat dengan Laravel & Filament.</p>
    </footer>

    <script>
        @if (isset($isSearching) && $isSearching && $firstMenuId)
        document.addEventListener('DOMContentLoaded', () => {
            const targetElement = document.getElementById('menu-{{ $firstMenuId }}');
            if (targetElement) {
                const offset = targetElement.offsetTop - 120; 
                window.scrollTo({
                    top: offset,
                    behavior: 'smooth'
                });
            }
        });
        @endif
    </script>
</body>
</html>