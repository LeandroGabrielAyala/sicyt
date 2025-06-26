<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBecario extends EditRecord
{
    protected static string $resource = BecarioResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.becarios.index') => 'Becarios',
            route('filament.admin.resources.becarios.edit', ['record' => $this->getRecord()]) => 'Becario: ' . $this->getRecord()->apellido . ', ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Becario: ' . $this->record->apellido . ', ' . $this->record->nombre;
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
                        ->title('Becario Eliminado')
                        ->body('El becario fue eliminado correctamente')
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
            ->title('Becario Actualizado')
            ->body('¡El becario fue actualizado correctamente!');
    }
}
