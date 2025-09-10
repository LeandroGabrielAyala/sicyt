<?php

namespace App\Filament\Resources\ConvocatoriaProyectoResource\Pages;

use App\Filament\Resources\ConvocatoriaProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConvocatoriaProyecto extends EditRecord
{
    protected static string $resource = ConvocatoriaProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
