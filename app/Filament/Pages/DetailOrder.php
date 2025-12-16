<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\Transaction;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class DetailOrder extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.detail-order';

    // Slug dengan parameter dinamis agar URL menjadi /admin/pos/order/{invoice}
    protected static ?string $slug = 'pos/order/{invoice}';

    // Sembunyikan dari sidebar navigasi karena halaman ini diakses via redirect/link
    protected static bool $shouldRegisterNavigation = false;

    public ?Transaction $transaction = null;
    public ?string $snapToken = null;
    public string $paymentMethod = 'cash';
    public ?array $data = [];

    public function mount(string $invoice): void
    {
        $this->transaction = Transaction::with([
            'user',
            'items.menu',
            'items.variant',
            'items.toppings.topping',
            'payments'
        ])->where('invoice', $invoice)->firstOrFail();

        if ($this->transaction->status !== 'paid') {
            $this->generateSnapToken();
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('paid_amount')
                    ->label('Pembayaran')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->live(onBlur: true),

                Placeholder::make('change_amount')
                    ->label('Kembalian')
                    ->content(function ($get) {
                        $total = (float) $this->transaction->total;
                        $pay = (float) ($get('paid_amount') ?? 0);
                        return 'Rp ' . number_format(max(0, $pay - $total), 0, ',', '.');
                    })
                    ->live(),
            ])
            ->statePath('data');
    }

    public function submitCash()
    {
        $data = $this->form->getState();
        $paid = (float) ($data['paid_amount'] ?? 0);
        $total = (float) $this->transaction->total;

        if ($paid < $total) {
            Notification::make()->title('Pembayaran kurang')->danger()->send();
            return;
        }

        $change = $paid - $total;

        $this->transaction->update([
            'status' => 'paid',
            'paid_amount' => $paid,
            'change_amount' => $change,
        ]);

        $this->transaction->payments()->create([
            'amount' => $paid,
            'status' => 'paid',
            'payment_method' => 'cash',
            'payment_date' => now(),
        ]);

        Notification::make()->title('Pembayaran Berhasil')->success()->send();

        return redirect()->to(static::getUrl(['invoice' => $this->transaction->invoice]));
    }

    public function handdleCallbackMidtrans()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $notif = new \Midtrans\Notification();
        $transaction = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $order_id = $notif->order_id;
        $order = Transaction::where('invoice', $order_id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        if ($transaction == 'capture') {
            if ($fraud == 'accept') {
                $this->updateOrderStatus($order, 'paid', $notif);
            }
        } else if ($transaction == 'cancel') {
            $this->updateOrderStatus($order, 'canceled', $notif);
        } else if ($transaction == 'deny') {
            $this->updateOrderStatus($order, 'failed', $notif);
        } else if ($transaction == 'settlement') {
            $this->updateOrderStatus($order, 'paid', $notif);
        }
    }

    protected function updateOrderStatus(Transaction $order, string $status, $notif)
    {
        $order->update(['status' => $status]);

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount' => $notif->total,
                'status' => $status,
                'payment_date' => in_array($status, ['settlement', 'paid']) ? now() : null,
            ],
        );
    }

    protected function applyMidtransResult(Transaction $trx, array $payload): void
    {
        $midtransStatus = $payload['transaction_status'] ?? null;   
        $paymentType = $payload['payment_type'] ?? 'midtrans';   

        $status = match ($midtransStatus) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'cancel', 'deny', 'expire' => 'failed',
            default => $trx->status,
        };

        $trx->update([
            'status' => $status,
            'paid_amount' => $status === 'paid' ? $trx->total : ($trx->paid_amount ?? 0),
            'change_amount' => 0,
        ]);

        Payment::updateOrCreate(
            ['transaction_id' => $trx->id],
            [
                'amount' => $trx->total,
                'status' => $status,
                'payment_method' => $paymentType,
                'payment_date' => $status === 'paid' ? now() : null,
                'snap_token' => $this->snapToken,
            ]
        );
    }


    public function handleMidtransSuccess(array $result): void
    {
        $this->applyMidtransResult($this->transaction, $result);

        Notification::make()->title('Pembayaran Berhasil')->success()->send();
        $this->redirect(static::getUrl(['invoice' => $this->transaction->invoice]));
    }


    public function generateSnapToken()
    {
        $transaction = $this->transaction;
        $payment = $transaction->payments()->latest()->first();
        $snap_token = '';

        if ($snap_token == null || $payment == null || $payment->status != 'paid') {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$clientKey = config('midtrans.client_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $transaction_details = array(
                'order_id' => $transaction->invoice,
                'gross_amount' => $transaction->total, // no decimal allowed for creditcard
            );

            $casier_details = array(
                'first_name' => $transaction->user->name ?? 'Guest',
                'email' => $transaction->user->email ?? 'guest@example.com',
            );

            $item_details = [];
            foreach ($transaction->items as $item) {
                $item_details[] = array(
                    'id' => $item->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->menu->name . ($item->variant ? ' - ' . $item->variant->name : ''),
                );
            }

            $checkout = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $casier_details,
                'item_details' => $item_details,
            );

            try {
                $snap_token = \Midtrans\Snap::getSnapToken($checkout);
                Payment::updateOrCreate(
                    ['transaction_id' => $transaction->id],
                    [
                        'amount' => $transaction->total,
                        'status' => 'pending',
                        'snap_token' => $snap_token,
                        'payment_method' => 'midtrans',
                    ],
                );
            } catch (\Exception $e) {
                Notification::make()->title($e->getMessage())->danger()->send();
            }

            $this->snapToken = $snap_token;
        }
    }

    public function getTitle(): string
    {
        return 'Detail Order #' . ($this->transaction->invoice ?? '');
    }
}