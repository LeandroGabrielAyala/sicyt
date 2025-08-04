<?php

namespace App\Filament\Imports;

use App\Models\Becario;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BecarioImporter extends Importer
{
    protected static ?string $model = Becario::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre'),
            ImportColumn::make('apellido'),
            ImportColumn::make('dni'),
            ImportColumn::make('cuil'),
            ImportColumn::make('fecha_nac'),
            ImportColumn::make('lugar_nac'),
            ImportColumn::make('domicilio'),
            ImportColumn::make('provincia'),
            ImportColumn::make('email'),
            ImportColumn::make('telefono'),
            ImportColumn::make('carrera_id'),
            ImportColumn::make('nivel_academico_id'),
            ImportColumn::make('disciplina_id'),
            ImportColumn::make('campo_id'),
            ImportColumn::make('objetivo_id'),
            ImportColumn::make('titulo'),
            ImportColumn::make('created_at'),
            ImportColumn::make('updated_at'),
        ];
    }

    public function resolveRecord(): ?Becario
    {
        return Becario::firstOrNew([
            'dni' => $this->data['dni'],
            'email' => $this->data['email'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Se completó la importación de becarios: ' . number_format($import->successful_rows) . ' ' . str('registro')->plural($import->successful_rows) . ' importado(s).';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('registro')->plural($failedRowsCount) . ' con errores.';
        }

        return $body;
    }
}
