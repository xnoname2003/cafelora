<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Daftar Menu Cafelora</title>

    @php
        use Illuminate\Support\Str; 
        $allCategories = $allCategories ?? collect(); 
        $bestsellers = $bestsellers ?? collect();
        $isSearching = request('search') || request('min_price') || request('max_price') || request('category') || request()->routeIs('customer.category.show');
        $firstMenuId = $filteredMenus[0]->id ?? null; // Digunakan di script scroll
    @endphp

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
            font-size: 3rem; 
            font-weight: 800; 
            line-height: 1;
            transform: translate(-50%, -50%);
            color: transparent; 
            -webkit-text-stroke: 1px #5D4037; 
            opacity: 0.8; 
            z-index: -1; 
            letter-spacing: 0.2rem; 
            white-space: nowrap; 
        }
        @media (min-width: 768px) {
            .menu-header-effect::before {
                font-size: 8rem; 
                letter-spacing: 0.5rem;
            }
        }
        .menu-header-main-text {
            font-size: 2.5rem; 
            font-weight: 800;
            color: #5D4037; 
            line-height: 1;
        }
        @media (min-width: 768px) {
            .menu-header-main-text {
                font-size: 3.5rem; 
            }
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

        #filter-menu {
            transition: max-height 0.5s ease-in-out, opacity 0.3s ease-in;
        }
    </style>
</head>

<body class="bg-soft-brown-200 min-h-screen">

    {{-- Navigasi (Logo & Tombol Filter) --}}
    <nav class="w-full bg-soft-brown-300 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto py-3 px-4 md:py-4 md:px-8">
            
            {{-- BARU: Main Nav Container (Menggabungkan Logo & Filter di Desktop) --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-center w-full">
                
                {{-- Bagian Kiri (Logo & Tombol Burger Mobile) --}}
                <div class="flex justify-between items-center w-full md:w-auto">
                    {{-- Logo --}}
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl md:text-3xl font-extrabold text-soft-brown-100">Cafelora</span>
                    </div>

                    {{-- Tombol Burger (Hanya tampil di mobile/layar kecil) --}}
                    <button id="filter-toggle" class="md:hidden p-2 text-soft-brown-700 bg-soft-brown-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-700">
                        <svg id="icon-menu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="icon-close" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                {{-- BARU: Kolom Filter (Rata Kanan di Desktop) --}}
                <form 
                    action="{{ route('customer.menu.index') }}" 
                    method="GET" 
                    class="w-full md:w-auto" {{-- Atur lebar otomatis di desktop --}}
                    id="filter-menu"
                >
                    
                    <div id="filter-content" class="md:flex md:space-x-3 hidden md:visible flex-col md:flex-row gap-3 mt-3 md:mt-0 items-end"> 
                        
                        {{-- Input Cari --}}
                        <div class="relative w-full md:w-auto min-w-[180px]"> 
                            <input 
                                type="text" 
                                placeholder="Cari nama menu..." 
                                name="search"
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 transition duration-150 text-soft-brown-700 bg-soft-brown-100 text-sm md:text-base"
                            >
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="h-5 w-5 text-soft-brown-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Input Harga Min --}}
                        <input 
                            type="number" 
                            placeholder="Harga Min" 
                            name="min_price"
                            value="{{ request('min_price') }}"
                            min="0"
                            class="w-full md:w-32 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100 text-sm md:text-base"
                        >

                        {{-- Input Harga Max --}}
                        <input 
                            type="number" 
                            placeholder="Harga Max" 
                            name="max_price"
                            value="{{ request('max_price') }}"
                            min="0"
                            class="w-full md:w-32 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100 text-sm md:text-base"
                        >
                        
                        {{-- Select Kategori --}}
                        <select 
                            name="category"
                            class="w-full md:w-40 px-4 py-2 border border-soft-brown-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-soft-brown-100 text-soft-brown-700 bg-soft-brown-100 text-sm md:text-base"
                        >
                            <option value="">-- Kategori --</option>
                            @foreach ($allCategories as $category)
                                <option 
                                    value="{{ $category->name }}"
                                    {{ request('category') == $category->name ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        
                        {{-- Tombol Cari --}}
                        <button 
                            type="submit" 
                            class="w-full md:w-auto px-4 py-2 bg-soft-brown-700 text-soft-brown-100 rounded-lg font-semibold hover:bg-olive-dark transition duration-150 text-sm md:text-base"
                        >
                            Cari
                        </button>
                        
                        {{-- Tombol Reset --}}
                        @if (request('search') || request('min_price') || request('max_price') || request('category'))
                            <a href="{{ route('customer.menu.index') }}" class="w-full md:w-auto px-4 py-2 bg-red-600 text-soft-brown-100 rounded-lg font-semibold hover:bg-red-700 text-center transition duration-150 text-sm md:text-base">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </nav>
    {{-- END Navigasi --}}
    
    <div class="pt-6 md:pt-8"> 
        
        {{-- HEADER BANNER (OUR MENU) --}}
        <header class="max-w-7xl mx-auto mb-8 md:mb-12 px-4 md:px-8">
            <div class="relative w-full h-48 md:h-72 rounded-xl overflow-hidden shadow-xl">
                
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
        {{-- END HEADER BANNER --}}
        
        <div class="max-w-7xl mx-auto space-y-12 md:space-y-20 px-4 md:px-8">

            {{-- BLOK MENU REKOMENDASI (BESTSELLER) --}}
            @if ($bestsellers->isNotEmpty() && !($isSearching ?? false))
                <section class="bg-soft-brown-100 p-6 md:p-8 rounded-xl shadow-2xl border-b-8 border-soft-brown-700">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 md:mb-8 space-y-2 md:space-y-0">
                        <h2 class="text-2xl md:text-4xl font-extrabold text-soft-brown-700 uppercase tracking-wider flex items-center">
                            <svg class="w-6 h-6 md:w-8 md:h-8 mr-2 md:mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path>
                            </svg>
                            Menu Rekomendasi
                        </h2>
                        <span class="text-base md:text-xl font-semibold text-soft-brown-500 bg-soft-brown-200 px-3 py-1 md:px-4 md:py-2 rounded-full">
                            Terlaris Saat Ini!
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($bestsellers as $menu)
                            @php
                                $detailUrl = route('customer.menu.show', $menu);
                                $salesQty = $menu->sales_qty ?? 0;
                                $salesColor = 'text-green-600'; 
                                $shortDescription = Str::words($menu->description, 15, '...');
                            @endphp
                            
                            {{-- Card Bestseller --}}
                            <a 
                                href="{{ $detailUrl }}" 
                                class="card bg-soft-brown-200 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out overflow-hidden transform relative" 
                                id="bestseller-{{ $menu->id }}"
                            >
                                <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg z-20 transform rotate-1">
                                    ⭐ BESTSELLER #{{ $loop->iteration }}
                                </div>
                                
                                <div class="relative h-32 md:h-48 w-full">
                                    <img src="{{ $menu->image ?? 'placeholder.png' }}" alt="Gambar {{ $menu->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-3 md:p-4">
                                    <h3 class="text-lg md:text-xl font-bold mb-1 text-soft-brown-700 fixed-title">{{ $menu->name }}</h3>
                                    
                                    <hr class="my-2 md:my-3 border-soft-brown-300">
                                    <div class="flex justify-between items-center">
                                        <p class="text-lg md:text-xl font-extrabold text-soft-brown-500">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                                        <div class="text-right">
                                            <span class="text-soft-brown-500 text-xs uppercase font-medium block">Terjual</span>
                                            <p class="text-sm md:text-md font-bold {{ $salesColor }}">{{ $salesQty }}x</p>
                                        </div>
                                    </div>
                                </div>
                            </a> 
                        @endforeach
                    </div>
                </section>
            @endif
            {{-- END BLOK BESTSELLER --}}


            {{-- LOGIKA DISPLAY MENU --}}
            @if (isset($isSearching) && $isSearching)
                {{-- BLOK 1: Tampilan saat ada filter/pencarian --}}
                
                @php
                    $searchTitle = "Hasil Pencarian";
                    if (request()->routeIs('customer.category.show')) { 
                        $searchTitle = "Menu Kategori: " . request()->route('name');
                    } elseif (request('category') && !request('search') && !request('min_price') && !request('max_price')) {
                        $searchTitle = "Hasil Kategori: " . request('category');
                    } elseif (request('search')) {
                        $searchTitle = "Hasil Pencarian: \"" . request('search') . "\"";
                    }

                    $groupedMenus = $filteredMenus->groupBy(function($menu) {
                        return $menu->category->name ?? 'Lainnya'; 
                    });
                @endphp

                <section>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-soft-brown-700 mb-6 uppercase tracking-wide">
                        {{ $searchTitle }}
                    </h2>
                    
                    @if ($filteredMenus->isEmpty())
                        <p class="text-lg text-soft-brown-500 italic">Menu tidak ditemukan dengan filter yang diterapkan.</p>
                    @else
                        @foreach ($groupedMenus as $categoryName => $menus)
                            <h3 class="text-xl md:text-2xl font-bold text-soft-brown-700 mb-4 mt-8">{{ $categoryName }}</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 mb-8 md:mb-10">
                                @foreach ($menus as $menu)
                                    @php
                                        $detailUrl = route('customer.menu.show', $menu);
                                        $salesQty = $menu->sales_qty ?? 0;
                                        $salesColor = ($salesQty < 10) ? 'text-red-600' : (($salesQty < 20) ? 'text-soft-brown-500' : (($salesQty < 30) ? 'text-yellow-600' : 'text-green-600'));
                                        $shortDescription = Str::words($menu->description, 15, '...');
                                    @endphp
                                    
                                    {{-- Card Menu Item (Detail lengkap) --}}
                                    <a 
                                        href="{{ $detailUrl }}" 
                                        class="card bg-soft-brown-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-500 ease-in-out overflow-hidden transform" 
                                        id="menu-{{ $menu->id }}"
                                    >
                                        <div class="relative h-48 md:h-56 w-full">
                                            <img src="{{ $menu->image }}" alt="Gambar {{ $menu->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4 md:p-6">
                                            <h3 class="text-xl md:text-2xl font-bold mb-1 text-soft-brown-700 fixed-title">{{ $menu->name }}</h3>
                                            
                                            <p class="mt-2 text-soft-brown-500 text-xs md:text-sm fixed-description">{{ $shortDescription ?? 'Deskripsi tidak tersedia.' }}</p>
                                            
                                            <hr class="my-3 md:my-4 border-soft-brown-200">
                                            <div class="flex justify-between items-center mb-3 md:mb-4">
                                                <div class="flex flex-col">
                                                    <span class="text-soft-brown-500 text-xs uppercase font-medium">Harga</span>
                                                    <p class="text-xl md:text-2xl font-extrabold text-soft-brown-500">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-soft-brown-500 text-xs uppercase font-medium block">Terjual</span>
                                                    <p class="text-base md:text-lg font-bold {{ $salesColor }}">{{ $salesQty }}x</p>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3 fixed-option-area">
                                                <div>
                                                    <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Pilihan Varian:</span>
                                                    <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                        @if ($menu->variants->isNotEmpty())
                                                            @foreach ($menu->variants as $variant)
                                                                <span class="px-2 py-0.5 md:px-3 md:py-1 bg-soft-brown-100 text-soft-brown-500 border border-soft-brown-500 rounded-full text-xs font-medium shadow-sm">
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
                                                                <span class="px-2 py-0.5 md:px-3 md:py-1 bg-soft-brown-500 text-soft-brown-100 rounded-full text-xs font-medium shadow-sm">
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
                {{-- BLOK 2: TAMPILAN DEFAULT BERDASARKAN KATEGORI --}}
                @foreach ($categories as $category)
                @if ($category->menus->isNotEmpty())
                <section>
                    <a href="{{ route('customer.category.show', ['name' => $category->name]) }}">
                        <h2 class="text-2xl md:text-3xl font-extrabold text-soft-brown-700 mb-6 uppercase tracking-wide hover:text-soft-brown-500 transition duration-150">
                            {{ $category->name }} &rarr;
                        </h2>
                    </a>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
                        @foreach ($category->menus as $menu)
                            @php
                                $detailUrl = route('customer.menu.show', $menu);
                                
                                $salesQty = $menu->sales_qty ?? 0;
                                $salesColor = ($salesQty < 10) ? 'text-red-600' : (($salesQty < 20) ? 'text-soft-brown-500' : (($salesQty < 30) ? 'text-yellow-600' : 'text-green-600'));

                                $shortDescription = Str::words($menu->description, 15, '...');
                            @endphp
                            
                            {{-- Card Menu Item (Detail lengkap) --}}
                            <a 
                                href="{{ $detailUrl }}" 
                                class="card bg-soft-brown-100 rounded-2xl shadow-lg hover:shadow-xl transition duration-500 ease-in-out overflow-hidden transform" 
                                id="menu-{{ $menu->id }}"
                            >
                                <div class="relative h-48 md:h-56 w-full">
                                    <img src="{{ $menu->image }}" alt="Gambar {{ $menu->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4 md:p-6">
                                    <h3 class="text-xl md:text-2xl font-bold mb-1 text-soft-brown-700 fixed-title">{{ $menu->name }}</h3>
                                    
                                    <p class="mt-2 text-soft-brown-500 text-xs md:text-sm fixed-description">{{ $shortDescription ?? 'Deskripsi tidak tersedia.' }}</p>
                                    
                                    <hr class="my-3 md:my-4 border-soft-brown-200">
                                    <div class="flex justify-between items-center mb-3 md:mb-4">
                                        <div class="flex flex-col">
                                            <span class="text-soft-brown-500 text-xs uppercase font-medium">Harga</span>
                                            <p class="text-xl md:text-2xl font-extrabold text-soft-brown-500">Rp {{ number_format($menu->base_price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-soft-brown-500 text-xs uppercase font-medium block">Terjual</span>
                                            <p class="text-base md:text-lg font-bold {{ $salesColor }}">{{ $salesQty }}x</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 fixed-option-area">
                                        <div>
                                            <span class="text-xs font-semibold text-soft-brown-700 block mb-1">Pilihan Varian:</span>
                                            <div class="flex flex-wrap gap-2 min-h-[28px]">
                                                @if ($menu->variants->isNotEmpty())
                                                    @foreach ($menu->variants as $variant)
                                                        <span class="px-2 py-0.5 md:px-3 md:py-1 bg-soft-brown-100 text-soft-brown-500 border border-soft-brown-500 rounded-full text-xs font-medium shadow-sm">
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
                                                        <span class="px-2 py-0.5 md:px-3 md:py-1 bg-soft-brown-500 text-soft-brown-100 rounded-full text-xs font-medium shadow-sm">
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
                @endif
                @endforeach
            @endif

        </div>
    </div>

    <footer class="mt-16 text-center text-soft-brown-500 text-sm pb-8 px-4 md:px-8">
        <p>&copy; {{ date('Y') }} Sistem {{ config('app.name', 'Restoran') }}. Dibuat dengan Laravel & Filament.</p>
    </footer>

    {{-- Script untuk scrolling hasil pencarian --}}
    @if (isset($isSearching) && $isSearching && ($firstMenuId ?? null))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var targetElement = document.getElementById('menu-{{ $firstMenuId }}'); 
            if (targetElement) {
                var offset = targetElement.offsetTop - 120;
                window.scrollTo({
                    top: offset,
                    behavior: 'smooth'
                });
            }
        });
    </script>
    @endif

    {{-- LOGIKA JAVASCRIPT UNTUK TOGGLE FILTER MOBILE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('filter-toggle');
            const filterContent = document.getElementById('filter-content');
            const iconMenu = document.getElementById('icon-menu');
            const iconClose = document.getElementById('icon-close');

            const isDesktop = window.matchMedia('(min-width: 768px)').matches;
            
            if (!isDesktop) {
                filterContent.style.maxHeight = '0';
                filterContent.style.opacity = '0';
                filterContent.style.overflow = 'hidden';
                filterContent.classList.remove('flex'); 
            }
            
            toggleButton.addEventListener('click', function() {
                if (!window.matchMedia('(min-width: 768px)').matches) {
                    const isHidden = filterContent.style.maxHeight === '0px' || filterContent.style.maxHeight === '';

                    if (isHidden) {
                        filterContent.style.display = 'flex'; 
                        filterContent.classList.add('flex'); 
                        filterContent.style.maxHeight = filterContent.scrollHeight + 'px'; 
                        filterContent.style.opacity = '1';
                        
                        iconMenu.classList.add('hidden');
                        iconClose.classList.remove('hidden');
                    } else {
                        filterContent.style.maxHeight = filterContent.scrollHeight + 'px';
                        
                        setTimeout(() => {
                            filterContent.style.maxHeight = '0';
                            filterContent.style.opacity = '0';
                        }, 10);

                        iconMenu.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    }
                }
            });

            window.addEventListener('resize', function() {
                const isDesktop = window.matchMedia('(min-width: 768px)').matches;
                if (isDesktop) {
                    filterContent.style.maxHeight = 'none'; 
                    filterContent.style.opacity = '1';
                    filterContent.style.overflow = 'visible';
                    filterContent.style.display = 'flex';
                    filterContent.classList.remove('hidden');
                    filterContent.classList.add('flex');
                    iconMenu.classList.remove('hidden'); 
                    iconClose.classList.add('hidden');
                } else {
                    filterContent.style.maxHeight = '0';
                    filterContent.style.opacity = '0';
                    filterContent.style.overflow = 'hidden';
                    iconMenu.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>