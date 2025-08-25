<?php

namespace App\Filament\Resources\CarreraResource\Pages;

use App\Filament\Resources\CarreraResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListCarreras extends ListRecords
{
    protected static string $resource = CarreraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Carrera'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.carreras.index') => 'Carreras',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Carreras';
    }
}
