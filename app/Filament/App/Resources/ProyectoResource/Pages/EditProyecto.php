<?php

namespace App\Filament\App\Resources\ProyectoResource\Pages;

use App\Filament\App\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
