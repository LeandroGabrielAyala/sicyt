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
            // Relaciones foráneas: pasamos el id, o podés ajustar para buscar por nombre si querés
            ImportColumn::make('nivel_academico_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('disciplina_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('campo_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('objetivo_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('titulo')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('titulo_posgrado')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('cargo_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('categoria_interna_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
            ImportColumn::make('incentivo_id')
                ->requiredMapping()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Investigador
    {
        // Evitar duplicados buscando por dni o cuil
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
