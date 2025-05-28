<?php

namespace App\Filament\Resources\ObjetivoResource\Pages;

use App\Filament\Resources\ObjetivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjetivo extends EditRecord
{
    protected static string $resource = ObjetivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
