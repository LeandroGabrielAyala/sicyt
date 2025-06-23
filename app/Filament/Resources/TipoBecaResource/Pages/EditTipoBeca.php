<?php

namespace App\Filament\Resources\TipoBecaResource\Pages;

use App\Filament\Resources\TipoBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoBeca extends EditRecord
{
    protected static string $resource = TipoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
