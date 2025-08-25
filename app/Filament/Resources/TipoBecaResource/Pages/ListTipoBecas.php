<?php

namespace App\Filament\Resources\TipoBecaResource\Pages;

use App\Filament\Resources\TipoBecaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoBecas extends ListRecords
{
    protected static string $resource = TipoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Tipo'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.tipo-beca.index') => 'Tipo de Becas',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Tipo de Becas';
    }
}
