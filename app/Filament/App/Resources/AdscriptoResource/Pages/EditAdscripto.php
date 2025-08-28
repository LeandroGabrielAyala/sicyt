<?php

namespace App\Filament\App\Resources\AdscriptoResource\Pages;

use App\Filament\App\Resources\AdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdscripto extends EditRecord
{
    protected static string $resource = AdscriptoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
