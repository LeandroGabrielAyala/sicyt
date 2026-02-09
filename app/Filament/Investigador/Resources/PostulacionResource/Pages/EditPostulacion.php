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

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }


    protected function getFormActions(): array
    {
        // Si ya no está en cargando → no mostrar botones
        if ($this->record->estado !== 'cargando') {
            return [];
        }

        return [
            Actions\Action::make('guardar_borrador')
                ->label('Guardar borrador')
                ->color('gray')
                ->icon('heroicon-o-document-text')
                ->action(function () {

                    $this->record->estado = 'cargando';

                    $this->save();

                    Notification::make()
                        ->title('Borrador guardado')
                        ->body('Podés continuar la carga más tarde.')
                        ->success()
                        ->send();
                }),


            Actions\Action::make('enviar')
                ->label('Enviar postulación')
                ->color('primary')
                ->icon('heroicon-o-paper-airplane')
                ->action(function () {

                    // 1️⃣ Guardar primero todo (archivos + datos)
                    $this->save();

                    // 2️⃣ Cambiar estado en DB
                    $this->record->update([
                        'estado' => 'pendiente',
                    ]);

                    Notification::make()
                        ->title('Postulación enviada')
                        ->body('La postulación fue enviada correctamente.')
                        ->success()
                        ->send();

                    // 3️⃣ Volver al listado
                    $this->redirect(PostulacionResource::getUrl());
                })


        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si venía rechazada y el investigador edita → vuelve a cargando
        if ($this->record->estado === 'rechazado') {
            $data['estado'] = 'cargando';
        }

        return $data;
    }



}
