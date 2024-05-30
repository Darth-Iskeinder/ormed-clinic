<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Filament\Resources\WorkResource\RelationManagers;
use App\Models\Customer;
use App\Models\History;
use App\Models\Service;
use App\Models\User;
use App\Models\Work;
use App\Tables\Columns\ArrayColumn;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;
    protected static ?string $label = 'Выполненные услуги';
    protected static ?string $navigationLabel = 'Выполненные услуги';
    protected static ?string $breadcrumb = 'Выполненные услуги';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if ($user->can('work-view')) {
            return true;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        $historyId = json_decode(request()->all()['components'][0]['snapshot'], true)['data']['ownerRecord'][1]['key'];
        $model = History::find($historyId);
        $services = $model->services;
        $options = [];
        foreach ($services as $service) {
            $options[$service['service']] = json_decode($service['service'], true)['name'];
        }


        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('notes')
                            ->label('Примечание'),
                        Forms\Components\Select::make('user_id')
                            ->label('Выберите сотрудника')
                            ->options(User::where('active', true)->pluck('name', 'id'))
                            ->searchable(),
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
                            ->columns(2),
                    ])
            ]);
    }

    protected function getRedirectUrl(): string
    {
        return redirect()->back();

    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь'),
                ArrayColumn::make('services')
                    ->label('Услуги'),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Примечание'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата'),
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make()
                ->after(function (Component $livewire) {
                    $livewire->dispatch('refreshProducts');
                })
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
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
//            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
