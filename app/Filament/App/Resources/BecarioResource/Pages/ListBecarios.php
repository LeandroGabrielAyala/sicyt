<?php

namespace App\Filament\App\Resources\BecarioResource\Pages;

use App\Filament\App\Resources\BecarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBecarios extends ListRecords
{
    protected static string $resource = BecarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.becarios.index') => 'Becarios',
            'Todos',
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Becarios';
    }
}
