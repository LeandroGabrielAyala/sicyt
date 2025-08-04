<?php

namespace App\Filament\Imports;

use App\Models\Adscripto;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AdscriptoImporter extends Importer
{
    protected static ?string $model = Adscripto::class;

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
            ImportColumn::make('codigo'),
            ImportColumn::make('email'),
            ImportColumn::make('telefono'),
            ImportColumn::make('carrera_id'),
            ImportColumn::make('titulo_id'),
            ImportColumn::make('created_at'),
            ImportColumn::make('updated_at'),
        ];
    }

    public function resolveRecord(): ?Adscripto
    {

        return Adscripto::firstOrNew([
            'dni' => $this->data['dni'],
            'email' => $this->data['email'],
        ]);

    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de adscriptos se completó. ' . number_format($import->successful_rows) . ' ' . str('registro')->plural($import->successful_rows) . ' importado(s).';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' registro(s) fallaron al importar.';
        }

        return $body;
    }
}
