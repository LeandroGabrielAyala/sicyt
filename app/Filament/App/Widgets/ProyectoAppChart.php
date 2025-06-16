<?php

namespace App\Filament\App\Widgets;

use App\Models\Proyecto;
use Filament\Widgets\ChartWidget;
use Filament\Facades\Filament;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ProyectoAppChart extends ChartWidget
{
    protected static ?string $heading = 'Estadistica de Proyectos';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // $data = Trend::query(Proyecto::query()->whereBelongsTo(Filament::getTenant()))
        //         ->between(
        //             start: now()->startOfMonth(),
        //             end: now()->endOfMonth(),
        //         )
        //         ->perDay()
        //         ->count();

        //     return [
        //         'datasets' => [
        //             [
        //                 'label' => 'Proyectos creados',
        //                 'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
        //             ],
        //         ],
        //         'labels' => $data->map(fn (TrendValue $value) => $value->date),
        //     ];
        $data = Trend::model(Proyecto::class)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Proyectos creados',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => $value->date),
            ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
