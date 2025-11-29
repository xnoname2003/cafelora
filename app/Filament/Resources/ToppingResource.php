<?php

namespace App\Filament\Resources;

use App\Models\Topping;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ToppingResource\Pages;

class ToppingResource extends Resource
{
    protected static ?string $model = Topping::class;
    protected static ?string $navigationGroup = 'Menu Management';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-vertical';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Topping')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('price')
                ->label('Harga')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Nama Topping')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('price')
                ->label('Harga')
                ->money('IDR'),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListToppings::route('/'),
            'create' => Pages\CreateTopping::route('/create'),
            'edit' => Pages\EditTopping::route('/{record}/edit'),
        ];
    }
}
