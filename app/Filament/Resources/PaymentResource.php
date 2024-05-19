<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Customer;
use App\Models\History;
use App\Models\Service;
use App\Tables\Columns\ArrayColumn;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = History::class;
    protected static ?string $label = 'Платежи';
    protected static ?string $navigationLabel = 'Платежи';
    protected static ?string $breadcrumb = 'Платежи';
    protected static ?string $activeNavigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Панель';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('payment-view')) {
            return true;
        }

        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Клиент'),
                ArrayColumn::make('services')
                    ->label('Услуги'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Сотрудник'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Общая сумма'),
                IconColumn::make('paid')
                    ->label('Оплачено')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->description(fn($record) => Carbon::parse($record->created_at)->format('H:i:s')),
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
            'index' => Pages\ListPayments::route('/'),
//            'create' => Pages\CreatePayment::route('/create'),
//            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
