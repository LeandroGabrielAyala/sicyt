<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRol extends CreateRecord
{
    protected static string $resource = RolResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Rol Creado')
            ->body('¡La Rol fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Rol';
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
                    RolResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
