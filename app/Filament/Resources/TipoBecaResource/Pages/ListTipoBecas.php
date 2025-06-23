<?php

namespace App\Filament\Resources\TipoBecaResource\Pages;

use App\Filament\Resources\TipoBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoBecas extends ListRecords
{
    protected static string $resource = TipoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
