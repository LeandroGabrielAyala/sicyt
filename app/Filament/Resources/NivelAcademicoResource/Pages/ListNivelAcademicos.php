<?php

namespace App\Filament\Resources\NivelAcademicoResource\Pages;

use App\Filament\Resources\NivelAcademicoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNivelAcademicos extends ListRecords
{
    protected static string $resource = NivelAcademicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
