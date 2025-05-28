<?php

namespace App\Filament\Resources\ObjetivoResource\Pages;

use App\Filament\Resources\ObjetivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObjetivos extends ListRecords
{
    protected static string $resource = ObjetivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
