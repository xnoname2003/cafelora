<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;

class BBestSellingProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris';

    protected int | string | array $columnSpan = 'half';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Menu::query()
                    ->join('transaction_items', 'menus.id', '=', 'transaction_items.menu_id')
                    ->select(
                        'menus.id', // <-- Tambahkan ini agar primary key terbaca
                        'menus.name',
                        'menus.stock',
                        DB::raw('SUM(transaction_items.quantity) as total_quantity')
                    )
                    ->groupBy('menus.id', 'menus.name', 'menus.stock')
                    ->orderByDesc('total_quantity')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Jumlah Terjual')
                    ->numeric(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok Tersedia')
                    ->numeric(),
            ])
            ->paginated(false) // Nonaktifkan paginasi
            ->searchable(false); // Nonaktifkan pencarian

            
    }
}
