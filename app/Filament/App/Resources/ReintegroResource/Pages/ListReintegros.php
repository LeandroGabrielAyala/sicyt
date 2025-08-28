<?php

namespace App\Filament\App\Resources\ReintegroResource\Pages;

use App\Filament\App\Resources\ReintegroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReintegros extends ListRecords
{
    protected static string $resource = ReintegroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
