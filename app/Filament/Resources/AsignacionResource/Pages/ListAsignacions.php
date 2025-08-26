<?php

namespace App\Filament\Resources\AsignacionResource\Pages;

use App\Filament\Resources\AsignacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsignacions extends ListRecords
{
    protected static string $resource = AsignacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Asignación'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.asignacion.index') => 'Asignaciones',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Asignaciones';
    }
}
