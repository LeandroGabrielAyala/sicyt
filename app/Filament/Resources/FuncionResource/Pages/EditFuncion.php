<?php

namespace App\Filament\Resources\FuncionResource\Pages;

use App\Filament\Resources\FuncionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuncion extends EditRecord
{
    protected static string $resource = FuncionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
