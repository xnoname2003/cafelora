<?php

namespace App\Filament\Pages;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;

class Pos extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $title = 'POS Kasir';
    protected static string $view = 'filament.pages.pos';

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

    public array $selectedVariant = [];
    public array $selectedToppings = [];
    public array $selectedQty = [];

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
        $this->menus = $this->queryMenus()->orderBy('name')->get();
        foreach ($this->menus as $menu) {
        $this->selectedToppings[$menu->id] ??= [];
        $this->selectedVariant[$menu->id] ??= null;
        $this->selectedQty[$menu->id] ??= 1;
    }

    }

    public function updatedSearch(): void
    {
        $this->loadMenus();
    }

    public function updatedSelectedCategory(): void
    {
        $this->loadMenus();
    }

    public function getForms(): array
    {
        return [
            'form' => $this->form(Forms\Form::make($this)),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Repeater::make('items')
                    ->label('Pesanan')
                    ->columns(6)
                    ->schema([
                        // Menu
                        Select::make('menu_id')
                            ->label('Menu')
                            ->options(Menu::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Varian
                        Select::make('variant_id')
                            ->label('Varian')
                            ->options(fn ($get) =>
                                $get('menu_id')
                                    ? Menu::find($get('menu_id'))?->variants->pluck('name', 'id')
                                    : []
                            )
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Topping
                        CheckboxList::make('toppings')
                            ->label('Topping')
                            ->options(fn ($get) =>
                                $get('menu_id')
                                    ? Menu::find($get('menu_id'))?->toppings->pluck('name', 'id')
                                    : []
                            )
                            ->columns(2)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->updatePrices($set, $get)),

                        // Qty
                        TextInput::make('qty')
                            ->label('Qty')
                            ->numeric()
                            ->default(1)
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

                // Pembayaran
                TextInput::make('paid_amount')
                    ->label('Pembayaran')
                    ->numeric()
                    ->reactive()
                    ->live(),

                // Kembalian real-time
                Placeholder::make('change_amount')
                    ->label('Kembalian')
                    ->content(function ($get) {
                        $total = (float) collect($get('items') ?? [])->sum('subtotal');
                        $pay   = (float) ($get('paid_amount') ?? 0);
                        return 'Rp ' . number_format(max(0, $pay - $total), 0, ',', '.');
                    })
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

        $qty = max(1, (int) ($this->selectedQty[$menuId] ?? 1));

        $variantId   = $this->selectedVariant[$menuId] ?? null;
        $variantName = '-';
        $adjust      = 0;
        if ($variantId) {
            $variant = $menu->variants->firstWhere('id', $variantId);
            if ($variant) {
                $variantName = $variant->name;
                $adjust      = (float) $variant->price_adjustment;
            }
        }

        $chosenToppings = Arr::wrap($this->selectedToppings[$menuId] ?? []);
        $validToppings  = $menu->toppings->whereIn('id', $chosenToppings)->pluck('id')->all();
        $extra          = (float) $menu->toppings->whereIn('id', $validToppings)->sum('price');

        $base      = (float) $menu->base_price;
        $unitPrice = $base + $adjust + $extra;
        $subtotal  = $qty * $unitPrice;

        $items   = $this->data['items'] ?? [];
        $items[] = [
            'menu_id'      => $menu->id,
            'menu_name'    => $menu->name,
            'variant_id'   => $variantId,
            'variant_name' => $variantName,
            'toppings'     => $validToppings,
            'qty'          => $qty,
            'base_price'   => $unitPrice,
            'subtotal'     => $subtotal,
        ];

        $this->data['items'] = $items;
        $this->form->fill($this->data);

        // reset input per-card
        $this->selectedQty[$menuId]      = 1;
        $this->selectedVariant[$menuId]  = null;
        $this->selectedToppings[$menuId] = [];
    }

    public function submit(): void
    {
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

        $transaction = Transaction::create([
            'user_id'       => Auth::id(),
            'invoice'       => 'INV-' . now()->timestamp,
            'status'        => $status,
            'total'         => $total,
            'paid_amount'    => $pay,
            'change_amount' => $change,
        ]);

        foreach ($this->data['items'] as $item) {
            // Simpan item
            $transactionItem = $transaction->items()->create([
                'menu_id'    => $item['menu_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity'   => (int) $item['qty'],
                'price'      => (int) $item['base_price'],
                'subtotal'   => (int) $item['subtotal'],
             ]);

            // Simpan topping per item
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
            }
        }

        Notification::make()
            ->title('Transaksi Berhasil')
            ->body(
                'Total: Rp ' . number_format($total, 0, ',', '.') .
                ' | Kembalian: Rp ' . number_format($change, 0, ',', '.')
            )
            ->success()
            ->send();

        // reset state setelah transaksi
        $this->data = ['items' => [], 'paid_amount' => null];
        $this->selectedQty = [];
        $this->selectedVariant = [];
        $this->selectedToppings = [];
        $this->form->fill($this->data);
        $this->loadMenus();
    }
}