<?php

namespace App\Filament\Imports;

use App\Models\Investigador;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class InvestigadorImporter extends Importer
{
    protected static ?string $model = Investigador::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('nombre')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('apellido')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('dni')
                ->requiredMapping()
                ->rules(['required', 'max:10']),

            ImportColumn::make('cuil')
                ->requiredMapping()
                ->rules(['required', 'max:15']),

            ImportColumn::make('fecha_nac')
                ->requiredMapping()
                ->rules(['required', 'date']),

            ImportColumn::make('lugar_nac')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('domicilio')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('provincia')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),

            ImportColumn::make('telefono')
                ->requiredMapping()
                ->rules(['required', 'max:20']),

            // 🔹 ESTE ERA EL ERROR
            // antes estaba "titulo"
            // ahora debe ser carrera_id

            ImportColumn::make('carrera_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('nivel_academico_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('disciplina_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('campo_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('objetivo_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('titulo_posgrado')
                ->requiredMapping()
                ->rules(['required', 'max:255']),

            ImportColumn::make('cargo_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('categoria_interna_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

            ImportColumn::make('incentivo_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),

        ];
    }

    public function resolveRecord(): ?Investigador
    {
        return Investigador::firstOrNew([
            'dni' => $this->data['dni'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Importación de investigadores completada: ' . number_format($import->successful_rows) . ' registros importados.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' registros fallaron.';
        }

        return $body;
    }
}