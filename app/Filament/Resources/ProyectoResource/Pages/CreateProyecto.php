<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProyecto extends CreateRecord
{
    protected static string $resource = ProyectoResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Proyecto Creado')
            ->body('¡El proyecto fue creado exitosamente!');
    }

    /*protected function getCreatedNotificationTitle(): ?string
    {
        return 'Proyecto Creado';
    }*/
}
