<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBecarios extends ListRecords
{
    protected static string $resource = BecarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
