<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListOfBenefitsResource\Pages;
use App\Filament\Resources\ListOfBenefitsResource\RelationManagers;
use App\Models\History;
use App\Models\ListOfBenefits;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListOfBenefitsResource extends Resource
{
    protected static ?string $model = Work::class;
    protected static ?string $label = 'Список доходов';
    protected static ?string $navigationGroup = 'Отчеты';
    protected static ?string $navigationLabel = 'Список доходов';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('list_of_benefits-view')) {
            return true;
        }

        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Сотрудник'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Общая выручка'),
                Tables\Columns\TextColumn::make('total_staff_amount')
                    ->label('Выручка сотрудника'),
                Tables\Columns\TextColumn::make('total_company_amount')
                    ->label('Выручка компании'),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_at'),
                    Forms\Components\DatePicker::make('created_until')
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
            'index' => Pages\ListListOfBenefits::route('/'),
            'create' => Pages\CreateListOfBenefits::route('/create'),
            'edit' => Pages\EditListOfBenefits::route('/{record}/edit'),
        ];
    }
}
