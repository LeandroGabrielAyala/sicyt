<?php

namespace App\Filament\Resources\PagoBecaResource\Pages;

use App\Filament\Resources\PagoBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagoBecas extends ListRecords
{
    protected static string $resource = PagoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
