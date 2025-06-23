<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProyecto extends EditRecord
{
    protected static string $resource = ProyectoResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.proyectos-de-investigacion.index') => 'Proyectos de Investigación',
            route('filament.admin.resources.proyectos-de-investigacion.edit', ['record' => $this->getRecord()]) => 'Proyecto N° ' . $this->getRecord()->nro,
            'Editar',
        ];
    }

    // Este método devuelve el breadcrumb para el registro que se está viendo
    public function getBreadcrumb(): string
    {
        return 'Editar PI N° ' . $this->getRecord()->nro;
    }

    public function getTitle(): string
    {
        return 'Editar Proyecto N° ' . $this->record->nro;
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
                        ->title('Proyecto Eliminado')
                        ->body('El proyecto fue eliminado correctamente')
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
            ->title('Proyecto Actualizado')
            ->body('¡El proyecto fue actualizado correctamente!');
    }

}
