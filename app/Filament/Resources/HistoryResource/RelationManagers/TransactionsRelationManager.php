<?php

namespace App\Filament\Resources\HistoryResource\RelationManagers;

use App\Filament\Resources\TransactionResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return TransactionResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TransactionResource::table($table);
    }
}
