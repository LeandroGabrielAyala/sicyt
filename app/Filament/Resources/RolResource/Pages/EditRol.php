<?php

namespace App\Filament\Resources\RolResource\Pages;

use App\Filament\Resources\RolResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRol extends EditRecord
{
    protected static string $resource = RolResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.rol.index') => 'Roles',
            route('filament.admin.resources.rol.edit', ['record' => $this->getRecord()]) => 'Rol: ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Rol: ' . $this->record->nombre;
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
                        ->title('Rol Eliminado')
                        ->body('El Rol fue eliminado correctamente')
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
            ->title('Rol Actualizado')
            ->body('¡El Rol fue actualizado correctamente!');
    }
}
