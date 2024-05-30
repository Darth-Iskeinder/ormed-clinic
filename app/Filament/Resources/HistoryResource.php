<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Filament\Resources\HistoryResource\RelationManagers;
use App\Models\Customer;
use App\Models\History;
use App\Models\Service;
use App\Models\Staff;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Tables\Columns\ArrayColumn;

class HistoryResource extends Resource
{
    protected static ?string $model = History::class;
    protected static ?string $label = 'Карту';
    protected static ?string $modelLabel = 'Клиентские карты';
    protected static ?string $pluralModelLabel = 'Клиентские карты';
    protected static ?string $navigationLabel = 'Клиентские карты';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $services = Service::all();
        $options = [];
        foreach ($services as $service) {
            $options[json_encode($service)] = $service->name;
        }

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Реферальная программа')
                            ->schema([
                                Forms\Components\TextInput::make('referrer')
                                    ->label('Пожалуйста, укажите имя человека, который порекомендовал клиента, если клиент пришел по рекомендации.')
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('referrer_reward')
                                    ->label('Пожалуйста, укажите сумму вознаграждения для человека, который порекомендовал клиента')
                                    ->integer()
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        Forms\Components\TextInput::make('diagnosis')
                            ->label('Опишите диагноз пациента'),
                        Forms\Components\Select::make('customer_id')
                            ->label('Выберите клиента')
                            ->options(Customer::all()->pluck('name', 'id'))
                            ->searchable()->required(),
                        Hidden::make('staff_id'),
                        Repeater::make('services')
                            ->schema([
                                Select::make('service')->required()
                                    ->label('Выберите услугу')
                                    ->options($options)
                                    ->searchable(),
                                Forms\Components\TextInput::make('count')
                                    ->label('Количество')
                                    ->integer()
                                    ->placeholder('Введите количество')
                                    ->default(1)
                                    ->required(),
                            ])
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->columns(2),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Итого к оплате')
                            ->numeric()
                            ->readOnly()
                            ->prefix('сом'),
                        View::make('card-info')
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'DESC');
    }

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
                TextColumn::make('diagnosis')
                    ->label('Диагноз пациента'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
            RelationManagers\WorksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistories::route('/'),
            'create' => Pages\CreateHistory::route('/create'),
            'edit' => Pages\EditHistory::route('/{record}/edit'),
//            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function updateTotals(Forms\Get $get, Forms\Set $set)
    {
        $total = 0;
        foreach($get('services') as $service) {
            $serviceInfo = json_decode($service['service'], true);
            if(!empty($serviceInfo) && !empty($service['count'])) {
                $total += (int) $serviceInfo['price'] * $service['count'];
            }
        }

        $set('total_amount', $total);
    }
}
