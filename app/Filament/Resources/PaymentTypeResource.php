<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentTypeResource\Pages;
use App\Filament\Resources\PaymentTypeResource\RelationManagers;
use App\Models\PaymentType;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class PaymentTypeResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $label = 'Оплата по источникам';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        $transactions = Transaction::select([
            DB::raw('MAX(id) as id'),
            'payment_type',
            DB::raw('SUM(sum) as sum'),
        ])
            ->groupBy('payment_type');

        return $table
            ->query($transactions)
            ->columns([
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Тип платежа'),
                Tables\Columns\TextColumn::make('sum')
                    ->label('Общая сумма'),
            ])
            ->defaultSort('payment_type')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at'),
                        DatePicker::make('created_until')
                            ->default(now())
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_at'] ?? null) {
                            $indicators['created_at'] = 'Created at ' . Carbon::parse($data['created_at'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPaymentTypes::route('/'),
//            'create' => Pages\CreatePaymentType::route('/create'),
//            'edit' => Pages\EditPaymentType::route('/{record}/edit'),
        ];
    }
}
