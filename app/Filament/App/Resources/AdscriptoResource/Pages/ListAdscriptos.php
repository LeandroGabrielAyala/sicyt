<?php

namespace App\Filament\App\Resources\AdscriptoResource\Pages;

use App\Filament\App\Resources\AdscriptoResource;
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
