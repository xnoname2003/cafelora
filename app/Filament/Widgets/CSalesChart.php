<?php

namespace App\Filament\Widgets;


use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Filament\Tables;
use Filament\Tables\Table;

class CSalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan';

    // Menambahkan filter di pojok kanan atas chart
    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            '7d' => '7 Hari Terakhir',
            'month' => 'Bulan Ini',
            'year' => 'Tahun ini',
        ];
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        switch ($this->filter) {
            case 'today':
                $salesData = Transaction::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(total) as total')
                )
                    ->whereDate('created_at', Carbon::today())
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->pluck('total', 'hour')
                    ->all();

                // Inisialisasi data untuk 24 jam dengan nilai 0
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                    $data[] = $salesData[$i] ?? 0;
                }
                break;

            case '7d':
                $startDate = Carbon::now()->subDays(6);
                $endDate = Carbon::now();

                $salesData = Transaction::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total')
                )
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get()
                    ->keyBy('date');

                // Inisialisasi data untuk 7 hari dengan nilai 0
                for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                    $formattedDate = $date->format('Y-m-d');
                    $labels[] = $date->format('d M');
                    $data[] = $salesData->has($formattedDate) ? $salesData[$formattedDate]->total : 0;
                }
                break;

            case 'month':
                $salesData = Transaction::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total) as total')
                )
                    ->whereYear('created_at', Carbon::now()->year)
                    ->groupBy('year', 'month')
                    ->orderBy('month', 'ASC')
                    ->get();

                $labels = $salesData->map(fn($item) => Carbon::create()->month($item->month)->format('M'));
                $data = $salesData->pluck('total')->toArray();
                break;

            case 'year':
                $salesData = Transaction::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(total) as total')
                )
                    ->groupBy('year')
                    ->orderBy('year', 'ASC')
                    ->get();

                $labels = $salesData->pluck('year')->toArray();
                $data = $salesData->pluck('total')->toArray();
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // protected static ?string $heading = 'Produk Terlaris';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Menu::query()
                    ->join('transaction_items', 'menus.id', '=', 'transaction_items.menu_id')
                    ->select(
                        'menus.id', // <-- Tambahkan ini agar primary key terbaca
                        'menus.name',
                        DB::raw('SUM(transaction_items.quantity) as total_quantity')
                    )
                    ->groupBy('menus.id', 'menus.name')
                    ->orderByDesc('total_quantity')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->label('Jumlah Terjual')
                    ->numeric(),
            ])
            ->paginated(false) // Nonaktifkan paginasi
            ->searchable(false); // Nonaktifkan pencarian

            
    }

}
