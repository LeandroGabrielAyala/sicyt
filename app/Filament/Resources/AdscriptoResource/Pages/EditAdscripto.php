<?php

namespace App\Filament\Resources\AdscriptoResource\Pages;

use App\Filament\Resources\AdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditAdscripto extends EditRecord
{
    protected static string $resource = AdscriptoResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.adscriptos.index') => 'Adscriptos',
            route('filament.admin.resources.adscriptos.edit', ['record' => $this->getRecord()]) => 'Adscripto: ' . $this->getRecord()->apellido . ', ' . $this->getRecord()->nombre,
            'Editar',
        ];
    }

    // Este método devuelve el breadcrumb para el registro que se está viendo
    public function getBreadcrumb(): string
    {
        return 'Editar adscripto: ' . $this->getRecord()->apellido . ', ' . $this->getRecord()->nombre;
    }

    public function getTitle(): string
    {
        return 'Editar adscripto: ' . $this->getRecord()->apellido . ', ' . $this->getRecord()->nombre;
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
                        ->title('Adscripto Eliminado')
                        ->body('El adscripto fue eliminado correctamente')
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
            ->title('Adscripto Actualizado')
            ->body('¡El adscripto fue actualizado correctamente!');
    }
}
