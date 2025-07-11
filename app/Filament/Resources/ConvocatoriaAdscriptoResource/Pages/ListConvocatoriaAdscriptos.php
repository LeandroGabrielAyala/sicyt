<?php

namespace App\Filament\Resources\ConvocatoriaAdscriptoResource\Pages;

use App\Filament\Resources\ConvocatoriaAdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConvocatoriaAdscriptos extends ListRecords
{
    protected static string $resource = ConvocatoriaAdscriptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
