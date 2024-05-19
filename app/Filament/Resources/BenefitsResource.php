<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BenefitsResource\Pages;
use App\Filament\Resources\BenefitsResource\RelationManagers;
use App\Models\Benefits;
use App\Models\Customer;
use App\Models\History;
use App\Models\Staff;
use App\Models\Work;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class BenefitsResource extends Resource
{
    protected static ?string $model = Work::class;
    protected static ?string $label = 'Доход по сотрудникам';
    protected static ?string $breadcrumb = 'Доход по сотрудникам';
    protected static ?string $navigationLabel = 'Доход по сотрудникам';
    protected static ?string $navigationGroup = 'Отчеты';
    protected static ?string $activeNavigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('benefits-view')) {
            return true;
        }

        return false;
    }

    public static function table(Table $table): Table
    {
        $histories=  Work::select([
            DB::raw('MAX(id) as id'),

            'user_id',
        DB::raw('SUM(total_amount) as total_amount'),
        DB::raw('SUM(total_staff_amount) as total_staff_amount'),
        DB::raw('SUM(total_company_amount) as total_company_amount')
    ])
        ->groupBy('user_id');

        return $table
            ->query($histories)
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
            ->defaultSort('user_id')
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
            'index' => Pages\ListBenefits::route('/'),
//            'create' => Pages\CreateBenefits::route('/create'),
//            'edit' => Pages\EditBenefits::route('/{record}/edit'),
        ];
    }
}
