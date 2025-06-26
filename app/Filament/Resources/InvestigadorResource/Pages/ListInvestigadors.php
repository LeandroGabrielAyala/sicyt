<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestigadors extends ListRecords
{
    protected static string $resource = InvestigadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Investigador'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.investigadores-pi.index') => 'Investigadores',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Investigadores';
    }
}
