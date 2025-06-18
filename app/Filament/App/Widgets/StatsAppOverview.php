<?php

namespace App\Filament\App\Widgets;

use App\Models\Actividad;
use App\Models\Proyecto;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAppOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make('Usuarios', Team::find(Filament::getTenant())->first()->members()->count())
            Stat::make('Usuarios', User::query()->count())
                ->description('Cantidad de Usuarios')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            // Stat::make('Actividad', Actividad::query()->whereBelongsTo(Filament::getTenant())->count())
            Stat::make('Actividad', Actividad::query()->count())
                ->description('Todos los Equipos')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            // Stat::make('Proyectos', Proyecto::query()->whereBelongsTo(Filament::getTenant())->count())
            Stat::make('Proyectos', Proyecto::query()->count())
                ->description('Total de Proyectos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
