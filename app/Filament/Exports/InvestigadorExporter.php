<?php

namespace App\Filament\Exports;

use App\Models\Investigador;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvestigadorExporter extends Exporter
{
    protected static ?string $model = Investigador::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('nombre')->label('Nombre'),
            ExportColumn::make('apellido')->label('Apellido'),
            ExportColumn::make('dni')->label('DNI'),
            ExportColumn::make('cuil')->label('CUIL'),
            ExportColumn::make('fecha_nac')->label('Fecha de Nacimiento'),
            ExportColumn::make('lugar_nac')->label('Lugar de Nacimiento'),
            ExportColumn::make('domicilio')->label('Domicilio'),
            ExportColumn::make('provincia')->label('Provincia'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('telefono')->label('Teléfono'),

            ExportColumn::make('nivelAcademico.nombre')->label('Nivel Académico'),
            ExportColumn::make('disciplina.nombre')->label('Disciplina'),
            ExportColumn::make('campo.nombre')->label('Campo'),
            ExportColumn::make('objetivo.nombre')->label('Objetivo'),

            ExportColumn::make('titulo')->label('Título'),
            ExportColumn::make('titulo_posgrado')->label('Título Posgrado'),

            ExportColumn::make('cargo.nombre')->label('Cargo'),
            ExportColumn::make('categoriaInterna.categoria')->label('Categoría Interna'),
            ExportColumn::make('incentivo.categoria')->label('Incentivo'),

            ExportColumn::make('created_at')->label('Creado'),
            ExportColumn::make('updated_at')->label('Actualizado'),
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
            ->title('Exportación de Investigadores')
            ->body(self::getCompletedNotificationBody($export))
            ->success()
            ->sendToDatabase($user)
            ->read(false);
    }
}
