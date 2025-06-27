<?php

namespace App\Filament\Resources\PagoBecaResource\Pages;

use App\Filament\Resources\PagoBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPagoBeca extends EditRecord
{
    protected static string $resource = PagoBecaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
