<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $label = 'Услуги';
    protected static ?string $navigationLabel = 'Услуги';
    protected static ?string $breadcrumb = 'Услуги';
    protected static ?string $activeNavigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Панель';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название услуги')
                            ->placeholder('Введите название услуги')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->label('Цена')
                            ->integer()
                            ->required(),
                        Forms\Components\TextInput::make('payout_percentage')
                            ->label('% выплаты')
                            ->integer()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название услуги')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('price')
                    ->label('Цена')
                    ->suffix(' Сом')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payout_percentage')
                    ->label('% Выплаты')
                    ->suffix('%')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->description(fn($record) => Carbon::parse($record->created_at)->format('H:i:s')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Дата обновления')
                    ->searchable()
                    ->sortable()
                    ->date()
                    ->description(fn($record) => Carbon::parse($record->updated_at)->format('H:i:s')),
            ])->defaultSort('price')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
