<?php

namespace App\Filament\Resources\ObjetivoResource\Pages;

use App\Filament\Resources\ObjetivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObjetivo extends ViewRecord
{
    protected static string $resource = ObjetivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
