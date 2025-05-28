<?php

namespace App\Filament\Resources\CampoResource\Pages;

use App\Filament\Resources\CampoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCampo extends ViewRecord
{
    protected static string $resource = CampoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
