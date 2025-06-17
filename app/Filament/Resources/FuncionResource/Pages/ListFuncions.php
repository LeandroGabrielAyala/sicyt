<?php

namespace App\Filament\Resources\FuncionResource\Pages;

use App\Filament\Resources\FuncionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuncions extends ListRecords
{
    protected static string $resource = FuncionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
