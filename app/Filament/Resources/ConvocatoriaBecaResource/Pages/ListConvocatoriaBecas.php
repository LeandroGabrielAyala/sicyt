<?php

namespace App\Filament\Resources\ConvocatoriaBecaResource\Pages;

use App\Filament\Resources\ConvocatoriaBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConvocatoriaBecas extends ListRecords
{
    protected static string $resource = ConvocatoriaBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
