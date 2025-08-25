<?php

namespace App\Filament\Resources\PagoBecaResource\Pages;

use App\Filament\Resources\PagoBecaResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListPagoBecas extends ListRecords
{
    protected static string $resource = PagoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Lista de Pagos'),
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Pagos de Becas';
    }
}
