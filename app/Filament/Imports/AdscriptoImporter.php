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
        ];
    }

    public function resolveRecord(): ?Adscripto
    {
        // return Adscripto::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Adscripto();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your Adscripto import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
