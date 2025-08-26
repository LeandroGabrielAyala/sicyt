<?php

namespace App\Filament\Resources\AsignacionResource\Pages;

use App\Filament\Resources\AsignacionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAsignacion extends EditRecord
{
    protected static string $resource = AsignacionResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.asignacion.index') => 'Asignaciones',
            route('filament.admin.resources.asignacion.edit', ['record' => $this->getRecord()]) => 'Asignación: ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Asignación: ' . $this->record->nombre;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Ver'),
            Actions\DeleteAction::make()
                ->label('Eliminar')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Asignación Eliminada')
                        ->body('La Asignación fue eliminada correctamente')
                ),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->submit('save'), // <- Esto hace que funcione realmente

            Action::make('cancelar')
                ->label('Cancelar')
                ->action(fn () => $this->redirect($this->getResource()::getUrl()))
                ->color('gray'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Asignación Actualizada')
            ->body('¡La Asignación fue actualizada correctamente!');
    }
}
