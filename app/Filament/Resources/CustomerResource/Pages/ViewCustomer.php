<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя клиента'),
                TextColumn::make('phone')
                    ->label('номер телефона'),
                TextColumn::make('age')
                    ->label('возраст'),
                TextColumn::make('created_at')
                    ->label('Дата создания'),
                TextColumn::make('updated_at')
                    ->label('Дата обновления')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
