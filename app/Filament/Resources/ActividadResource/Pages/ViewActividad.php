<?php

namespace App\Filament\Resources\ActividadResource\Pages;

use App\Filament\Resources\ActividadResource;
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
