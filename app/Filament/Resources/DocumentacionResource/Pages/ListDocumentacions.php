<?php

namespace App\Filament\Resources\DocumentacionResource\Pages;

use App\Filament\Resources\DocumentacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentacions extends ListRecords
{
    protected static string $resource = DocumentacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
