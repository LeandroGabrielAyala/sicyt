<?php

namespace App\Filament\Exports;

use App\Models\Adscripto;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class AdscriptoExporter extends Exporter
{
    protected static ?string $model = Adscripto::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('apellido')->label('Apellido'),
            ExportColumn::make('nombre')->label('Nombre'),
            ExportColumn::make('dni')->label('DNI'),
            ExportColumn::make('cuil')->label('CUIL'),
            ExportColumn::make('fecha_nac')->label('Fecha de nacimiento'),
            ExportColumn::make('lugar_nac')->label('Lugar de nacimiento'),
            ExportColumn::make('domicilio')->label('Domicilio'),
            ExportColumn::make('provincia')->label('Provincia'),
            ExportColumn::make('codigo')->label('Código'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('telefono')->label('Teléfono'),
            ExportColumn::make('carrera.nombre')->label('Carrera'),
            ExportColumn::make('titulo.nombre')->label('Título'),

            ExportColumn::make('proyectos.nro')
                ->label('Número de Proyecto')
                ->formatStateUsing(fn ($record) => $record->proyectos->first()?->nro ?? ''),

            ExportColumn::make('proyectos.nombre')
                ->label('Proyecto')
                ->formatStateUsing(fn ($record) => $record->proyectos->first()?->nombre ?? ''),

            ExportColumn::make('convocatoria')
                ->label('Convocatoria')
                ->formatStateUsing(function ($record) {
                    return $record->proyectos
                        ->map(fn($proyecto) => $proyecto->pivot->convocatoriaAdscripto?->nombre)
                        ->filter()
                        ->unique()
                        ->implode(', ');
                }),


            ExportColumn::make('proyectos.pivot.vigente')
                ->label('Vigente')
                ->formatStateUsing(fn ($record) => $record->proyectos->first()?->pivot->vigente ? 'Sí' : 'No'),

            ExportColumn::make('proyectos.pivot.director_id')
                ->label('Director')
                ->formatStateUsing(fn ($record) => $record->proyectos->first()?->pivot->director?->nombre_completo ?? ''),

            ExportColumn::make('proyectos.pivot.codirector_id')
                ->label('Codirector')
                ->formatStateUsing(fn ($record) => $record->proyectos->first()?->pivot->codirector?->nombre_completo ?? ''),
        ];
    }

    public function query(): Builder
    {
        return Adscripto::query()
            ->with([
                'proyectos',
                'proyectos.pivot',
                'proyectos.pivot.director',
                'proyectos.pivot.codirector',
            ]);
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
            ->title('Exportación de Adscriptos')
            ->body(self::getCompletedNotificationBody($export))
            ->success()
            ->sendToDatabase($user)
            ->read(false);
    }
}
