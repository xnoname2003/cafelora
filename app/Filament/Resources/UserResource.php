<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    /**
     * FORM
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required(),

            TextInput::make('email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->password()
                ->revealable()
                ->label('Password')
                ->revealable()
                ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn($state) => filled($state))
                ->afterStateHydrated(fn(TextInput $component, $state) => $component->state('')),

            Select::make('roles')
                ->label('Roles')
                ->options(\Spatie\Permission\Models\Role::pluck('name', 'name'))
                ->preload()
                ->searchable()
                ->afterStateHydrated(function (Select $component, ?User $record) {
                    if ($record) {
                        $component->state($record->roles->pluck('name')->toArray());
                    }
                })
                ->dehydrateStateUsing(fn($state) => $state)
                ->afterStateUpdated(function ($state, ?User $record) {
                    if ($record) {
                        $record->syncRoles($state);
                    }
                }),
        ]);
    }

    /**
     * TABLE
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(function ($state) {
                        $role = is_array($state) ? ($state[0] ?? null) : $state;

                        return match ($role) {
                            'admin' => 'success',
                            'staff' => 'warning',
                            default => 'gray',
                        };
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * RELATIONS
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * PAGES
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    /**
     * AUTHORIZATION
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
