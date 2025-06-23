<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBecario extends EditRecord
{
    protected static string $resource = BecarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
