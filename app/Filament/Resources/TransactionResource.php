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
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Filament\Exports\TransactionReportExport;
use Filament\Tables\Contracts\HasTable;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;

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
            ->headerActions([
                Action::make('export_xlsx')
                    ->label('Export XLSX')
                    ->icon('heroicon-o-table-cells')
                    ->action(function (HasTable $livewire) {
                        $query = $livewire->getFilteredSortedTableQuery();
                        $filters = $livewire->tableFilters ?? [];
                        $startDate = data_get($filters, 'date_range.from')
                            ?? data_get($filters, 'date.created_at');
                        $endDate = data_get($filters, 'date_range.until')
                            ?? data_get($filters, 'date.created_at');
                        $filename = 'SalesReport-' . date('YmdHis') . '-' . Str::upper(Str::random(4)) . '.xlsx';

                        return Excel::download(
                            new TransactionReportExport($query, 'xlsx', $startDate, $endDate),
                            $filename,
                            ExcelWriter::XLSX
                        );
                    }),
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function (HasTable $livewire) {
                        $query = $livewire->getFilteredSortedTableQuery();
                        $filters = $livewire->tableFilters ?? [];
                        $startDate = data_get($filters, 'date_range.from')
                            ?? data_get($filters, 'date.created_at');
                        $endDate = data_get($filters, 'date_range.until')
                            ?? data_get($filters, 'date.created_at');
                        $filename = 'SalesReport-' . date('YmdHis') . '-' . Str::upper(Str::random(4)) . '.pdf';

                        return Excel::download(
                            new TransactionReportExport($query, 'pdf', $startDate, $endDate),
                            $filename,
                            ExcelWriter::DOMPDF
                        );
                    }),
                    
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Transaction $record) => DetailOrder::getUrl(['invoice' => $record->invoice])),

                Action::make('complete')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Transaction $record) => "Selesaikan Pesanan {$record->invoice}?")
                    ->action(function (Transaction $record) {
                        DB::transaction(function () use ($record) {
                            $record->update(['status' => 'completed']);
                            $record->payments()->where('transaction_id', $record->id)->update(['status' => 'completed']);
                        });

                        Notification::make()
                            ->title('Pesanan Telah Selesai')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Transaction $record): bool => $record->status === 'paid'),
                
                Action::make('print')
                    ->label('Print Struk')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record) => route('receipt.show', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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