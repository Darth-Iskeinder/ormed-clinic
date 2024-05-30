<?php

namespace App\Filament\Resources\ListOfBenefitsResource\Pages;

use App\Filament\Resources\ListOfBenefitsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListOfBenefits extends ListRecords
{
    protected static string $resource = ListOfBenefitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
