<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditComprobante extends EditRecord
{
    protected static string $resource = ComprobanteResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.comprobante.index') => 'Comprobantes',
            route('filament.admin.resources.comprobante.edit', ['record' => $this->getRecord()]) => 'Comprobante: ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Comprobante: ' . $this->record->nombre;
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
                        ->title('Comprobante Eliminado')
                        ->body('El Comprobante fue eliminado correctamente')
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
            ->title('Comprobante Actualizado')
            ->body('¡El Comprobante fue actualizado correctamente!');
    }
}
