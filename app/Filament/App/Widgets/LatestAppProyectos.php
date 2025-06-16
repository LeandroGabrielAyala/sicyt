<?php

namespace App\Filament\App\Widgets;

use App\Models\Proyecto;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAppProyectos extends BaseWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            // ->query(Proyecto::query()->whereBelongsTo(Filament::getTenant()))
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
