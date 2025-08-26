<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComprobantes extends ListRecords
{
    protected static string $resource = ComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Comprobante'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.comprobante.index') => 'Comprobantes',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Comprobantes';
    }
}
