<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\InvestigadorImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\CreateAction;

class ListInvestigadors extends ListRecords
{
    protected static string $resource = InvestigadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Investigador'),
            ImportAction::make()
                ->importer(InvestigadorImporter::class)
                ->label('Importar Investigadores')
                ->modalHeading('Subir archivo CSV de Investigadores'),
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
