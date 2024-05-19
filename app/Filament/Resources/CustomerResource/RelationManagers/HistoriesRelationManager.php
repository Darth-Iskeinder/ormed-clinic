<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Tables\Columns\ArrayColumn;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'histories';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->description(fn($record) => Carbon::parse($record->created_at)->format('H:i:s')),
            ])
            ->filters([
                //
            ]);
    }
}
