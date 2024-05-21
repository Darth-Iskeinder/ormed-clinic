<?php

namespace App\Filament\Resources\WorkResource\Pages;

use App\Filament\Resources\WorkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;

class EditWork extends EditRecord
{
    protected static string $resource = WorkResource::class;

    #[On('refreshProducts')]
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
