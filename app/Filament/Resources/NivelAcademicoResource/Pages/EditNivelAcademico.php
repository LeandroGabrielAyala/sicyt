<?php

namespace App\Filament\Resources\NivelAcademicoResource\Pages;

use App\Filament\Resources\NivelAcademicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNivelAcademico extends EditRecord
{
    protected static string $resource = NivelAcademicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
