<?php

namespace App\Filament\Exports;

use App\Models\Proyecto;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

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
            ExportColumn::make('campo.id'),
            ExportColumn::make('objetivo.id'),
            ExportColumn::make('actividad.id'),
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
        $body = 'Your proyecto export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
