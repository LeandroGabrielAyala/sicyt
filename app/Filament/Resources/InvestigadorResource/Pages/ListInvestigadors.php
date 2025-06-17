<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestigadors extends ListRecords
{
    protected static string $resource = InvestigadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
