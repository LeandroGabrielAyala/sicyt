<?php

namespace App\Filament\Resources\CategoriaInternaResource\Pages;

use App\Filament\Resources\CategoriaInternaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoriaInterna extends EditRecord
{
    protected static string $resource = CategoriaInternaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
