<?php

namespace App\Filament\Resources\RubroResource\Pages;

use App\Filament\Resources\RubroResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRubro extends EditRecord
{
    protected static string $resource = RubroResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.rubro.index') => 'Rubros',
            route('filament.admin.resources.rubro.edit', ['record' => $this->getRecord()]) => 'Rubro: ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Rubro: ' . $this->record->nombre;
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
                        ->title('Rubro Eliminado')
                        ->body('El Rubro fue eliminado correctamente')
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
            ->title('Rubro Actualizado')
            ->body('¡El Rubro fue actualizado correctamente!');
    }
}
