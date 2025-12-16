<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use App\Filament\Pages\DetailOrder;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Menu Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('invoice')
                    ->label('Invoice')
                    ->disabled()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->disabled(),

                Forms\Components\TextInput::make('paid_amount')
                    ->label('Pembayaran')
                    ->numeric()
                    ->disabled(),

                Forms\Components\TextInput::make('change_amount')
                    ->label('Kembalian')
                    ->numeric()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'completed' => 'success',
                        'cancelled'  => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Pembayaran')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('change_amount')
                    ->label('Kembalian')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                ]),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('created_at')
                            ->label('Tanggal Transaksi'),
                ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn ($query, $date) => $query->whereDate('created_at', $date),
                            );
                }),
                    Tables\Filters\Filter::make('date_range')
                        ->form([
                            Forms\Components\DatePicker::make('from')->label('Dari'),
                            Forms\Components\DatePicker::make('until')->label('Sampai'),
                        ])
                        ->query(function ($query, array $data) {
                            return $query
                                ->when(
                                    $data['from'],
                                    fn ($query, $date) => $query->whereDate('created_at', '>=', $date),
                                )
                                ->when(
                                    $data['until'],
                                    fn ($query, $date) => $query->whereDate('created_at', '<=', $date),
                                );
                        }),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Transaction $record) => DetailOrder::getUrl(['invoice' => $record->invoice])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Action::make('print')
                    ->label('Print Struk')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('receipt.show', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
            RelationManagers\ToppingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
