<?php

namespace App\Filament\Resources\ConvocatoriaAdscriptoResource\Pages;

use App\Filament\Resources\ConvocatoriaAdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConvocatoriaAdscripto extends EditRecord
{
    protected static string $resource = ConvocatoriaAdscriptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
