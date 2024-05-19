<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use BladeUI\Icons\Components\Icon;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $label = 'Транзакции';
    protected static ?string $navigationLabel = 'Транзакции';
    protected static ?string $breadcrumb = 'Транзакции';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if ($user->can('transaction-show')) {
            return true;
        }

        return false;
    }

    protected function getRedirectUrl(): string
    {
        return redirect()->back();

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sum')
                    ->required()
                    ->integer()
                    ->maxLength(255),
                Toggle::make('accept')
                    ->label('Оплата')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь'),
                Tables\Columns\TextColumn::make('sum')
                    ->label('Сумма'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата'),
                Tables\Columns\IconColumn::make('accept')
                ->label('Опачено/Возврат')
                ->boolean()
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make()
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshHistory');
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([

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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
