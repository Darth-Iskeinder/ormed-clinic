<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Customer;
use App\Models\History;
use App\Models\Service;
use App\Tables\Actions\ApproveAction;
use App\Tables\Columns\ArrayColumn;
use Filament\Forms\Set;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = History::class;
    protected static ?string $label = 'Счета к получению';
    protected static ?string $modelLabel = 'Счета к получению';
    protected static ?string $pluralModelLabel = 'Счета к получению';
    protected static ?string $navigationLabel = 'Счета к получению';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('invoice-view')) {
            return false;
        }

        return false;
    }

    public static function rejectForm(Form $form, $history): Form
    {
        $services = $history->services;
        $options = [];
        foreach ($services as $service) {
            $options[$service['service']] = json_decode($service['service'], true)['name'];
        }

        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Repeater::make('reject_services')
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
                                self::updateRejectTotals($get, $set);
                            })
                            ->columns(2),
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->readOnly()
                            ->prefix('сом')
                    ])
            ]);
    }

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
                        Forms\Components\TextInput::make('total')
                            ->label('Итого к оплате')
                            ->numeric()
                            ->readOnly()
                            ->prefix('сом'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Клиент'),
                ArrayColumn::make('services')
                    ->label('Услуги'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Cумма к оплате'),
                IconColumn::make('paid')
                    ->label('Оплачено')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Подтвердить_платеж')
                ->button()
                ->action(function (History $history) {
                    $history->paid = 1;
                    $history->paid_date = now();
                    $history->save();
                })
                ->requiresConfirmation(),
                EditAction::make('Возрат')
                    ->button()
                    ->form(function (History $history, Form $form) {
                        return self::rejectForm($form, $history);
                    }),

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
            'index' => Pages\ListInvoices::route('/'),
//            'create' => Pages\CreateInvoice::route('/create'),
//            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }

    public static function updateTotals(Forms\Get $get, Forms\Set $set)
    {
        $total = 0;
        $charge = 0;
//        dd($get('services'));
        foreach($get('services') as $service) {
            $serviceInfo = json_decode($service['service'], true);
            if(!empty($serviceInfo) && !empty($service['count'])) {
                $total += (int) $serviceInfo['price'] * $service['count'];
            }
        }

        $set('total', $total);
        $set('charge', $charge);
    }

    public static function updateRejectTotals(Forms\Get $get, Forms\Set $set)
    {
        $total = 0;
        foreach($get('reject_services') as $service) {
            $serviceInfo = json_decode($service['service'], true);
            if(!empty($serviceInfo) && !empty($service['count'])) {
                $total += (int) $serviceInfo['price'] * $service['count'];
            }
        }

        $set('total', $total);
    }
}
