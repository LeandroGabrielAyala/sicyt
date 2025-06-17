<?php

namespace App\Filament\Resources\IncentivoResource\Pages;

use App\Filament\Resources\IncentivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncentivo extends EditRecord
{
    protected static string $resource = IncentivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
