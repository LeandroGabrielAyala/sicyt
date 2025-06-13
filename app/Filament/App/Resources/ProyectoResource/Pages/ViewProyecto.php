<?php

namespace App\Filament\App\Resources\ProyectoResource\Pages;

use App\Filament\App\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProyecto extends ViewRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
