<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\OrderMetricsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class SalesTrendChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Tendencia';

    protected int|string|array $columnSpan = 2;

    protected ?string $maxHeight = '300px';

    public ?string $filter = 'sales';

    protected function getData(): array
    {
        $service = app(OrderMetricsService::class);
        $period = data_get($this->pageFilters, 'period');

        $metric = $this->filter === 'gross_profit' ? 'gross_profit' : 'sales';
        $trend = $service->getTrendData($period, $metric);

        return [
            'datasets' => [
                [
                    'label' => 'Periodo actual',
                    'data' => $trend['current'],
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.18)',
                ],
                [
                    'label' => 'Periodo anterior',
                    'data' => $trend['previous'],
                    'borderColor' => '#64748b',
                    'backgroundColor' => 'rgba(100, 116, 139, 0.14)',
                ],
            ],
            'labels' => $trend['labels'],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'sales' => 'Ventas',
            'gross_profit' => 'Utilidad',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
