<?php

namespace App\Filament\Imports;

use App\Models\Proyecto;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProyectoImporter extends Importer
{
    protected static ?string $model = Proyecto::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nro')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('nombre')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('resumen')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('campo')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('objetivo')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('actividad')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('duracion')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('inicio')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('fin')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('estado')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('disposicion')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('resolucion')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('pdf_disposicion'),
            ImportColumn::make('pdf_resolucion'),
            ImportColumn::make('presupuesto')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Proyecto
    {
        // return Proyecto::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Proyecto();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your proyecto import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
