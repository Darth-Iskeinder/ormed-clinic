<?php

namespace App\Filament\Resources\ListOfBenefitsResource\Pages;

use App\Filament\Resources\ListOfBenefitsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListOfBenefits extends EditRecord
{
    protected static string $resource = ListOfBenefitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
