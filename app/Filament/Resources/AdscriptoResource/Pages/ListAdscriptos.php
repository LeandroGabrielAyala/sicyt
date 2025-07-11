<?php

namespace App\Filament\Resources\AdscriptoResource\Pages;

use App\Filament\Resources\AdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdscriptos extends ListRecords
{
    protected static string $resource = AdscriptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
