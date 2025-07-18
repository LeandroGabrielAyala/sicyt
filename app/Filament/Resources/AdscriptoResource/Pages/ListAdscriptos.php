<?php

namespace App\Filament\Resources\AdscriptoResource\Pages;

use App\Filament\Resources\AdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Imports\AdscriptoImporter;
use Filament\Actions\ImportAction;
use Filament\Actions\CreateAction;

class ListAdscriptos extends ListRecords
{
    protected static string $resource = AdscriptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Adscripto'),
            ImportAction::make()
                ->importer(AdscriptoImporter::class)
                ->label('Importar Adscriptos')
                ->modalHeading('Subir archivo CSV de Adscriptos'),
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Adscriptos';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.adscriptos.index') => 'Adscriptos',
            'Todos',
        ];
    }

}
