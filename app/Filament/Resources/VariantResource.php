<?php

namespace App\Filament\Resources;

use App\Models\Variant;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\VariantResource\Pages;
use Filament\Forms\Components\TextInput;

class VariantResource extends Resource
{
    protected static ?string $model = Variant::class;
    protected static ?string $navigationGroup = 'Menu Management';
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required(),

            TextInput::make('price_adjustment')
                ->numeric()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('price_adjustment')->sortable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVariants::route('/'),
            'create' => Pages\CreateVariant::route('/create'),
            'edit'   => Pages\EditVariant::route('/{record}/edit'),
        ];
    }
}
