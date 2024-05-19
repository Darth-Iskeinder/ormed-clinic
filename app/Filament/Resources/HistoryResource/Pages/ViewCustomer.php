<?php

namespace App\Filament\Resources\HistoryResource\Pages;

use App\Filament\Resources\HistoryResource;
use App\Models\History;
use App\Tables\Columns\ArrayColumn;
use Carbon\Carbon;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class ViewCustomer extends ViewRecord
{
    public static string $resource = HistoryResource::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Клиент')
                    ->searchable()
                    ->sortable(),
                ArrayColumn::make('services')
                    ->label('Услуги'),
                IconColumn::make('paid')
                    ->label('Оплачено')
                    ->boolean(),
                IconColumn::make('completed')
                    ->label('Выполнено')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->sortable()
                    ->date()
                    ->description(fn($record) => Carbon::parse($record->created_at)->format('H:i:s')),
            ])
            ->filters([
                //
            ])
            ->actions([

            ]);
    }

}
