<?php

namespace App\Filament\Resources\AdscriptoResource\Pages;

use App\Filament\Resources\AdscriptoResource;
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
