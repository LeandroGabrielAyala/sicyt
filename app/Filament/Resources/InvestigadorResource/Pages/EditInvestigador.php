<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestigador extends EditRecord
{
    protected static string $resource = InvestigadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
