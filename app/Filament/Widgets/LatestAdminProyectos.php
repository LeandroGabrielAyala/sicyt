<?php

namespace App\Filament\Widgets;

use App\Models\Proyecto;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminProyectos extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Proyecto::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nro')
                    ->label('Nro. PI'),
                TextColumn::make('resolucion')
                    ->label('Resolución'),
                TextColumn::make('disposicion')
                    ->label('Disposición'),
            ]);
    }
}
