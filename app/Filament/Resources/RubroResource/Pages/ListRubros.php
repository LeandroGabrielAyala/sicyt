<?php

namespace App\Filament\Resources\RubroResource\Pages;

use App\Filament\Resources\RubroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRubros extends ListRecords
{
    protected static string $resource = RubroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Rubro'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.rubro.index') => 'Rubros',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Rubros';
    }
}
