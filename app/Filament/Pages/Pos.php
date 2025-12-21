<?php

namespace App\Filament\Pages;

use App\Filament\Pages\DetailOrder;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Str;

class Pos extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $title = 'POS Kasir';
    protected static string $view = 'filament.pages.pos';
    protected static ?string $navigationGroup = 'Menu Transactions';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    // State form
    public ?array $data = [
        'items' => [],
        'paid_amount' => null,
    ];

    // Daftar menu untuk grid
    public $menus = [];

    public $showCart = false;
    public $showReceipt = false;
    public ?Transaction $transaction = null;
    
    // Filter
    public string $search = '';
    public ?int $selectedCategory = null;

    public function mount(): void
    {
        $this->loadMenus();
        $this->form->fill($this->data);
    }

    protected function queryMenus()
    {
        $query = Menu::with(['category', 'variants', 'toppings']);
        if ($this->selectedCategory === -1) {
        return $query->orderByDesc('sales_qty')->take(5);
        } else

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if (trim($this->search) !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    public function loadMenus(): void
    {
        if ($this->selectedCategory === -1) {
        $this->menus = $this->queryMenus()
            ->orderByDesc('sales_qty')
            ->take(5)
            ->get();
        }
        
        else {$this->menus = $this
            ->queryMenus()
            ->orderBy('name')
            ->get();
        }

        // Logika baru untuk menyesuaikan stok yang ditampilkan berdasarkan item di keranjang
        $cartQuantities = collect($this->data['items'] ?? [])
            ->groupBy('menu_id')
            ->map(fn ($items) => $items->sum(fn ($item) => (int) ($item['qty'] ?? 0)));

        $this->menus->each(function ($menu) use ($cartQuantities) {
            if ($cartQuantities->has($menu->id)) {
                // Kurangi stok menu dengan kuantitas yang ada di keranjang
                $menu->stock = max(0, $menu->stock - $cartQuantities->get($menu->id));
            }
        });
    }

    public function updatedSearch(): void
    {
        $this->loadMenus();
    }

    public function updatedSelectedCategory(): void
    {
        $this->loadMenus();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Repeater::make('items')
                    ->label('Pesanan')
                    ->columns(1)
                    ->extraAttributes(['class' => 'w-[313px]'])
                    ->addActionLabel('Tambah Pesanan')
                    ->reactive()
                    ->afterStateUpdated(fn () => $this->loadMenus())
                    ->schema([
                        // Menu
                        Select::make('menu_id')
                            ->label('Menu')
                            ->placeholder('Pilih menu')
                            ->options(
                                 Menu::where('stock', '>', 0)->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Varian
                        Select::make('variant_id')
                            ->label('Varian')
                            ->placeholder('Pilih varian')
                            ->options(fn ($get) =>
                                $get('menu_id')
                                    ? Menu::find($get('menu_id'))?->variants->pluck('name', 'id')
                                    : []
                            )
                            ->reactive()
                            ->required(fn ($get) => Menu::find($get('menu_id'))?->variants->count() > 0)
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Qty
                        TextInput::make('qty')
                            ->label('Qty')
                            ->numeric()
                            ->default(1)
                            ->reactive()
                            ->live()
                            ->required()
                            ->rules([
                                function (callable $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        // Dapatkan path dan key dari item yang sedang diedit
                                        $itemPath = Str::beforeLast($attribute, '.');
                                        $itemKey = Str::afterLast($itemPath, '.');

                                        $menuId = $get($itemPath . '.menu_id');
                                        if (!$menuId) {
                                            return;
                                        }

                                        $menu = Menu::find($menuId);
                                        if (!$menu) {
                                            return;
                                        }

                                        $originalStock = $menu->stock;

                                        // Ambil semua item dari keranjang
                                        $allItems = $get('..');

                                        // Hitung total kuantitas untuk menu yang sama di item LAIN dalam keranjang
                                        $otherItemsQty = 0;
                                        if (is_array($allItems)) {
                                            foreach ($allItems as $key => $item) {
                                                if ($key !== $itemKey && isset($item['menu_id']) && $item['menu_id'] == $menuId) {
                                                    $otherItemsQty += (int) ($item['qty'] ?? 0);
                                                }
                                            }
                                        }

                                        $availableStockForItem = $originalStock - $otherItemsQty;

                                        if ((int) $value > $availableStockForItem) {
                                            $fail("Kuantitas melebihi stok. Sisa stok yang bisa diinput untuk item ini adalah {$availableStockForItem}.");
                                        }
                                    };
                                },
                            ])
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Topping
                        CheckboxList::make('toppings')
                            ->label('Topping')
                            ->options(fn ($get) =>
                                $get('menu_id')
                                    ? Menu::find($get('menu_id'))?->toppings->pluck('name', 'id')
                                    : []
                            )
                            ->columns(1)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Harga satuan
                        TextInput::make('base_price')
                            ->label('Harga satuan')
                            ->numeric()
                            ->disabled(),

                        // Subtotal
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->default([]),

                // Total real-time
                Placeholder::make('total')
                    ->label('Total')
                    ->content(fn ($get) =>
                        'Rp ' . number_format((float) collect($get('items') ?? [])->sum('subtotal'), 0, ',', '.')
                    )
                    ->live(),
            ]);
    }

    protected function updatePrices(callable $set, callable $get): void
    {
        $menu = Menu::with(['variants', 'toppings'])->find($get('menu_id'));
        if (! $menu) {
            $set('base_price', 0);
            $set('subtotal', 0);
            return;
        }

        $base     = (float) ($menu->base_price ?? 0);
        $variant  = $menu->variants->firstWhere('id', $get('variant_id'));
        $adjust   = (float) ($variant?->price_adjustment ?? 0);
        $toppings = $get('toppings') ?? [];
        $extra    = (float) $menu->toppings->whereIn('id', $toppings)->sum('price');
        $qty      = max(1, (int) ($get('qty') ?? 1));

        $unitPrice = $base + $adjust + $extra;
        $set('base_price', $unitPrice);
        $set('subtotal', $qty * $unitPrice);
    }

    public function addToCart(int $menuId): void
    {
        $menu = Menu::with(['variants', 'toppings'])->find($menuId);
        if (! $menu) return;

        $qty = 1; // default qty
        $base = (float) $menu->base_price;

        $items = $this->data['items'] ?? [];
        $items[] = [
            'menu_id'    => $menu->id,
            'menu_name'  => $menu->name,
            'variant_id' => null,
            'toppings'   => [],
            'qty'        => $qty,
            'base_price' => $base,
            'subtotal'   => $qty * $base,
        ];

        $this->data['items'] = $items;
        $this->form->fill($this->data);
        $this->loadMenus();
    }

    public function submit()
    {
        // 1. Panggil validasi form secara eksplisit.
        // Ini akan menjalankan semua aturan, termasuk validasi stok live yang sudah kita buat.
        // Jika ada error, proses akan berhenti di sini dan menampilkan pesan di bawah input yang salah.
        $this->form->validate();

        // 2. Sebagai pengaman tambahan (best practice), kita lakukan validasi akhir yang komprehensif
        // terhadap keseluruhan keranjang sebelum membuat transaksi. Ini mencegah race condition.
        $items = collect($this->data['items'] ?? []);
        $menuQuantities = $items->groupBy('menu_id')->map(fn ($group) => $group->sum('qty'));

        foreach ($menuQuantities as $menuId => $totalQty) {
            $menu = Menu::find($menuId);
            if ($menu && $menu->stock < $totalQty) {
                Notification::make()
                    ->title('Stok Tidak Cukup!')
                    ->body("Total pesanan untuk '{$menu->name}' ({$totalQty}) melebihi stok yang tersedia ({$menu->stock}). Silakan periksa kembali keranjang Anda.")
                    ->danger()
                    ->send();

                return; // Hentikan proses checkout
            }
        }

        $items = collect($this->data['items'] ?? []);
        if ($items->isEmpty()) {
            Notification::make()
                ->title('Keranjang kosong')
                ->danger()
                ->send();
            return;
        }

        $total  = (float) $items->sum('subtotal');
        $pay    = (float) ($this->data['paid_amount'] ?? 0);
        $change = max(0, $pay - $total);
        $status = $pay >= $total ? 'paid' : 'pending';

        // Hitung nomor antrian (reset setiap hari)
        $today = now()->startOfDay();
        $lastQueue = Transaction::where('created_at', '>=', $today)->max('queue_number');
        $queueNumber = $lastQueue ? $lastQueue + 1 : 1;

        $transaction = Transaction::create([
            'user_id'       => Auth::id(),
            'invoice'       => 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
            'queue_number'  => $queueNumber,
            'status'        => $status,
            'total'         => $total,
            'paid_amount'   => $pay,
            'change_amount' => $change,
        ]);

        foreach ($this->data['items'] as $item) {
            $transactionItem = $transaction->items()->create([
                'menu_id'    => $item['menu_id'],
                'variant_id' => empty($item['variant_id']) ? null : $item['variant_id'],
                'quantity'   => (int) $item['qty'],
                'price'      => (int) $item['base_price'],
                'subtotal'   => (int) $item['subtotal'],
            ]);

                        foreach ($item['toppings'] as $toppingId) {
                $topping = \App\Models\Topping::find($toppingId);
                if ($topping) {
                    $transactionItem->toppings()->create([
                        'topping_id' => $topping->id,
                        'price'      => (int) $topping->price,
                        'quantity'   => (int) $item['qty'],
                    ]);
                }
            }
        }

        // Otomatisasi stok
        foreach ($items as $item) {
            $menu = Menu::find($item['menu_id']);
            if ($menu) {
                $menu->decrement('stock', $item['qty']);
                $menu->increment('sales_qty', $item['qty']);
            }
        }

        Notification::make()
            ->title('Pesanan Berhasil Dibuat')
            ->body(
                'Total: Rp ' . number_format($total, 0, ',', '.') .
                ($status === 'paid' ? ' (Lunas)' : ' (Belum Lunas)')
            )
            ->success()
            ->send();

        // reset state setelah transaksi
        $this->data = ['items' => [], 'paid_amount' => null];
        $this->form->fill($this->data);
        $this->loadMenus();

        return redirect()->to(DetailOrder::getUrl(['invoice' => $transaction->invoice]));
    }
}