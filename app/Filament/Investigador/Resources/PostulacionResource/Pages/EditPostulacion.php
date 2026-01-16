<?php

namespace App\Filament\Investigador\Resources\PostulacionResource\Pages;

use App\Filament\Investigador\Resources\PostulacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPostulacion extends EditRecord
{
    protected static string $resource = PostulacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        if ($this->record->estado !== 'cargando') {
            return [];
        }

        return [
            // Guardar borrador
            Actions\Action::make('guardar_borrador')
                ->label('Guardar borrador')
                ->color('gray')
                ->icon('heroicon-o-document-text')
                ->action(fn () => $this->save()),

            // Enviar postulación
            Actions\Action::make('enviar')
                ->label('Enviar postulación')
                ->color('primary')
                ->icon('heroicon-o-paper-airplane')
                ->action(function () {
                    $this->record->estado = 'pendiente';
                    $this->save();
                }),
        ];
    }

}
