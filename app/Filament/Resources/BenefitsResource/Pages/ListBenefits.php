<?php

namespace App\Filament\Resources\BenefitsResource\Pages;

use App\Filament\Resources\BenefitsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBenefits extends ListRecords
{
    protected static string $resource = BenefitsResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
