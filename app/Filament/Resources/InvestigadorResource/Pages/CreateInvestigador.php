<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateInvestigador extends CreateRecord
{
    protected static string $resource = InvestigadorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Investigador Creado')
            ->body('¡El Investigador fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Investigador';
    }

    public function getBreadcrumb(): string
    {
        return 'Crear nuevo';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar')
                ->submit('create')
                ->successRedirectUrl(
                    InvestigadorResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
