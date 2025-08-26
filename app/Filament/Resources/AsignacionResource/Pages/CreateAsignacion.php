<?php

namespace App\Filament\Resources\AsignacionResource\Pages;

use App\Filament\Resources\AsignacionResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAsignacion extends CreateRecord
{
    protected static string $resource = AsignacionResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Asignación Creada')
            ->body('¡La Asignación fue creada exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nueva Asignación';
    }

    public function getBreadcrumb(): string
    {
        return 'Crear nueva';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar')
                ->submit('create')
                ->successRedirectUrl(
                    AsignacionResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
