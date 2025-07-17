<?php

namespace App\Filament\Resources\InvestigadorResource\Pages;

use App\Filament\Resources\InvestigadorResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvestigador extends EditRecord
{
    protected static string $resource = InvestigadorResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.investigadores-pi.index') => 'Investigadores',
            route('filament.admin.resources.investigadores-pi.edit', ['record' => $this->getRecord()]) => 'Investigador: ' . $this->getRecord()->apellido . ', ' . $this->getRecord()->nombre, 'Editar',
        ];
    }

    public function getTitle(): string
    {
        return 'Editar Investigador: ' . $this->record->apellido . ', ' . $this->record->nombre;
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
                        ->title('Investigador Eliminado')
                        ->body('El investigador fue eliminado correctamente')
                ),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->submit('save'), // ✅ Traducción personalizada
            Action::make('cancelar')
                ->label('Cancelar')
                ->action(fn () => $this->redirect($this->getResource()::getUrl()))
                ->color('gray')
                ->button(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Investigador Actualizado')
            ->body('¡El investigador fue actualizado correctamente!');
    }

}
