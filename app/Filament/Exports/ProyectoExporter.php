<?php

namespace App\Filament\Exports;

use App\Models\Proyecto;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProyectoExporter extends Exporter
{
    protected static ?string $model = Proyecto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nro'),
            ExportColumn::make('nombre'),
            ExportColumn::make('resumen'),
            ExportColumn::make('campo.nombre'),
            ExportColumn::make('objetivo.nombre'),
            ExportColumn::make('actividad.nombre'),
            ExportColumn::make('duracion'),
            ExportColumn::make('inicio'),
            ExportColumn::make('fin'),
            ExportColumn::make('estado'),
            ExportColumn::make('disposicion'),
            ExportColumn::make('resolucion'),
            ExportColumn::make('pdf_disposicion'),
            ExportColumn::make('pdf_resolucion'),
            ExportColumn::make('presupuesto'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación se completó correctamente. ' . number_format($export->successful_rows) . ' fila(s) exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fila(s) fallaron.';
        }

        return $body;
    }

    public static function getCompletedNotification(Export $export): ?Notification
    {
        $user = $export->user ?? auth()->user;

        if (! $user) {
            Log::warning('No se pudo identificar el usuario para enviar la notificación de exportación.');
            return null;
        }


        return Notification::make()
            ->title('Exportación de Proyectos')
            ->body(self::getCompletedNotificationBody($export))
            ->success()
            ->sendToDatabase($user) // esto guarda en la base
            ->read(false); // ← asegura que aparezca como NO LEÍDA
    }


}
