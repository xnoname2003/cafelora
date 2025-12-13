<?php

namespace App\Filament\Resources;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Variant;
use App\Models\Topping;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\MenuResource\Pages;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn; 
use Filament\Tables\Columns\ImageColumn; 
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;
    protected static ?string $navigationGroup = 'Menu Management';
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';


    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Select::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('name')
                ->label('Nama Menu')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(3),

            Forms\Components\TextInput::make('base_price')
                ->label('Harga Dasar')
                ->numeric()
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Gambar (Maks 2MB)')
                ->disk('public') 
                ->directory('menu-images') 
                ->visibility('public')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->image()
                ->dehydrated(fn ($state) => filled($state))
                ->nullable(),

            Forms\Components\TextInput::make('stock')
                ->label('Stok')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('variants')
                ->relationship('variants', 'name')
                ->multiple()
                ->label('Varian Menu')
                ->preload(), 
            
            Forms\Components\Select::make('toppings')
                ->relationship('toppings', 'name')
                ->multiple()
                ->label('Topping Menu')
                ->preload(), 
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40) 
                    ->tooltip(fn ($state): ?string => $state) 
                    ->wrap() 
                    ->sortable(),
                
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar'), 

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR') 
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable(),

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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}