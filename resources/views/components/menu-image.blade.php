@props([
    'menu',
    'class' => '',
])

@php
    $name = $menu->name ?? 'Menu';
    $imageSrc = 'https://placehold.co/1920x1080?text=' . $name . '&font=roboto';

    if (!empty($menu->image)) {
        if (\Illuminate\Support\Str::startsWith($menu->image, 'menu-images/')) {
            $imageSrc = asset('storage/' . $menu->image);
        } elseif (\Illuminate\Support\Str::startsWith($menu->image, 'images/cafelora-menu/')) {
            $imageSrc = asset($menu->image);
        } else {
            $imageSrc = asset($menu->image);
        }
    }
@endphp

<img src="{{ $imageSrc }}" alt="Gambar {{ $name }}" class="{{ $class }}">
