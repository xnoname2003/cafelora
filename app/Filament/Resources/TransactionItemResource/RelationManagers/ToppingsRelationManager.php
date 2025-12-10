<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToppingsRelationManager extends RelationManager
{
    protected static string $relationship = 'toppings';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('topping_id')
                ->relationship('topping', 'name')
                ->required(),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->default(1),
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),
        ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('topping.name')
            ->columns([
                Tables\Columns\TextColumn::make('topping.name')
                    ->label('Topping'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->label('Harga per unit'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn ($record) => $record->price * $record->quantity)
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
