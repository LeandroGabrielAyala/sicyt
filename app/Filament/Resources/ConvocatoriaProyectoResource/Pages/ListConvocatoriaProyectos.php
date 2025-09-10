<?php

namespace App\Filament\Resources\ConvocatoriaProyectoResource\Pages;

use App\Filament\Resources\ConvocatoriaProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConvocatoriaProyectos extends ListRecords
{
    protected static string $resource = ConvocatoriaProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
