<?php

namespace App\Filament\Resources\ConvocatoriaBecaResource\Pages;

use App\Filament\Resources\ConvocatoriaBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConvocatoriaBeca extends EditRecord
{
    protected static string $resource = ConvocatoriaBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
