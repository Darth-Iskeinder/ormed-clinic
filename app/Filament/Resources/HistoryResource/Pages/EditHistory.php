<?php

namespace App\Filament\Resources\HistoryResource\Pages;

use App\Filament\Resources\HistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditHistory extends EditRecord
{
    protected static string $resource = HistoryResource::class;

    #[On('refreshHistory')]
    public function refreshForm(): void
    {
        parent::refreshFormData(array_keys($this->record->toArray()));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
