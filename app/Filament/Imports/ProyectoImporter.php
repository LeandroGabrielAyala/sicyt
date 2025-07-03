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

            ImportColumn::make('campo'),
            ImportColumn::make('objetivo'),
            ImportColumn::make('actividad'),

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
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Proyecto
    {
        return new Proyecto([
            'nro' => $this->data['nro'],
            'nombre' => $this->data['nombre'],
            'resumen' => $this->data['resumen'],
            'duracion' => (int) $this->data['duracion'],
            'inicio' => \Carbon\Carbon::parse($this->data['inicio']),
            'fin' => \Carbon\Carbon::parse($this->data['fin']),
            'estado' => filter_var($this->data['estado'], FILTER_VALIDATE_BOOLEAN),
            'disposicion' => $this->data['disposicion'],
            'resolucion' => $this->data['resolucion'],
            'pdf_disposicion' => $this->transformPdfField($this->data['pdf_disposicion']),
            'pdf_resolucion' => $this->transformPdfField($this->data['pdf_resolucion']),
            'presupuesto' => (float) str_replace(',', '.', $this->data['presupuesto']),
            'campo_id' => $this->resolveRelationId('campo', $this->data['campo']),
            'objetivo_id' => $this->resolveRelationId('objetivo', $this->data['objetivo']),
            'actividad_id' => $this->resolveRelationId('actividad', $this->data['actividad']),
        ]);
    }

    protected function resolveRelationId(string $relationName, string $label): ?int
    {
        $modelInstance = new ($this->getModel());
        $relationship = $modelInstance->{$relationName}();
        $relatedModel = $relationship->getRelated();

        return $relatedModel::where('nombre', $label)->value('id');
    }

    protected function transformPdfField(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $files = explode(',', $value);

        return json_encode(
            collect($files)
                ->map(fn($file) => trim($file))
                ->toArray()
        );
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de proyectos finalizó: ' . number_format($import->successful_rows) . ' fila(s) importadas correctamente.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' fila(s) no pudieron importarse.';
        }

        return $body;
    }
}
