<?php

namespace App\Filament\Resources\TipoBecaResource\Pages;

use App\Filament\Resources\TipoBecaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditTipoBeca extends EditRecord
{
    protected static string $resource = TipoBecaResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.tipo-beca.index') => 'Tipo de Becas',
            route('filament.admin.resources.tipo-beca.edit', ['record' => $this->getRecord()]) => 'Tipo de Beca: ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Tipo de Beca: ' . $this->record->nombre;
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
                        ->title('Tipo de Beca Eliminado')
                        ->body('La Carrera fue eliminado correctamente')
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
            ->title('Tipo de Beca Actualizado')
            ->body('¡El Tipo de Beca fue actualizado correctamente!');
    }
}
