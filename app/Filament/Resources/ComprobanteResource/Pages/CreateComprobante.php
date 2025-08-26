<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateComprobante extends CreateRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Comprobante Creado')
            ->body('¡El Comprobante fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Comprobante';
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
                    ComprobanteResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
