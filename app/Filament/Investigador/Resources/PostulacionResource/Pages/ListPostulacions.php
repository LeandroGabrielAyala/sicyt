<?php

namespace App\Filament\Investigador\Resources\PostulacionResource\Pages;

use App\Filament\Investigador\Resources\PostulacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostulacions extends ListRecords
{
    protected static string $resource = PostulacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
