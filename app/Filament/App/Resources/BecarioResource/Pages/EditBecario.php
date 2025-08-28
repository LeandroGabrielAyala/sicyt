<?php

namespace App\Filament\App\Resources\BecarioResource\Pages;

use App\Filament\App\Resources\BecarioResource;
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
