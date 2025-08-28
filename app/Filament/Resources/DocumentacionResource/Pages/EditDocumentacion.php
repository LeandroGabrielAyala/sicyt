<?php

namespace App\Filament\Resources\DocumentacionResource\Pages;

use App\Filament\Resources\DocumentacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentacion extends EditRecord
{
    protected static string $resource = DocumentacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
