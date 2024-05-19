<?php

namespace App\Filament\Resources\BenefitsResource\Pages;

use App\Filament\Resources\BenefitsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBenefits extends EditRecord
{
    protected static string $resource = BenefitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
