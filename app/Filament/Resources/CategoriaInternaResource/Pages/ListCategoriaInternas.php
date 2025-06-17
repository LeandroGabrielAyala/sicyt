<?php

namespace App\Filament\Resources\CategoriaInternaResource\Pages;

use App\Filament\Resources\CategoriaInternaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoriaInternas extends ListRecords
{
    protected static string $resource = CategoriaInternaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
