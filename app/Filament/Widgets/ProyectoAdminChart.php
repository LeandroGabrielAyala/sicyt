<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ProyectoAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Estadistica de Proyectos';

    protected static ?int $sort = 3;

    protected static string $color = 'warning';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Proyectos creados.',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']
        ];
    }

    protected function getType(): string
    {
        // return 'bar';
        return 'line';
    }
}
