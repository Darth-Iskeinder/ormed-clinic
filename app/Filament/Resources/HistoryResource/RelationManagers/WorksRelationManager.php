<?php

namespace App\Filament\Resources\HistoryResource\RelationManagers;

use App\Filament\Resources\WorkResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorksRelationManager extends RelationManager
{
    protected static string $relationship = 'works';

    public function form(Form $form): Form
    {
        return WorkResource::form($form);
    }

    public function table(Table $table): Table
    {
        return WorkResource::table($table);
    }
}
