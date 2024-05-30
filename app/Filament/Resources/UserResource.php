<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Персонал';
    protected static ?string $navigationLabel = 'Персонал';
    protected static ?string $breadcrumb = 'Персонал';
    protected static ?string $navigationGroup = 'Панель';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('user-view')) {
            return true;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Имя'),
                TextInput::make('specialization')
                    ->label('Специализация'),
                TextInput::make('email')
                    ->label('Email'),
                TextInput::make('password')
                    ->label('Пароль'),
                Forms\Components\Select::make('role_id')
                ->label('Выберите роль')
                ->options(Role::all()->pluck('name', 'id'))
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('active', true)
            ->orderBy('created_at', 'DESC');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя'),
                TextColumn::make('specialization')
                    ->label('Специализация'),
                TextColumn::make('email')
                    ->label('Логин'),
                Tables\Columns\TextColumn::make('role.name')
                    ->label('Роль'),
                TextColumn::make('created_at')
                    ->label('Дата создания'),
                TextColumn::make('updated_at')
                    ->label('Дата обновления')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Удалить')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (User $user) {
                        $user->active = false; // Переключение значения поля active
                        $user->save();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
