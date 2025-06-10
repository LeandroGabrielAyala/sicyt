<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProyecto extends CreateRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Proyecto Creado';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
                    ->success()
                    ->title('Proyecto Creado')
                    ->body('¡El Proyecto fue creado Exitosamente!');
    }
}
