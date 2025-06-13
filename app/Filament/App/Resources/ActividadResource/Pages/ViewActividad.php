<?php

namespace App\Filament\App\Resources\ActividadResource\Pages;

use App\Filament\App\Resources\ActividadResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewActividad extends ViewRecord
{
    protected static string $resource = ActividadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
