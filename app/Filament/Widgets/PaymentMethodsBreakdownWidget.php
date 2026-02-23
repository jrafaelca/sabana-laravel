<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\OrderMetricsService;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PaymentMethodsBreakdownWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Mix de métodos de pago';

    protected int|string|array $columnSpan = 2;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $service = app(OrderMetricsService::class);
        $period = data_get($this->pageFilters, 'period');

        $rows = $service->getPaymentMethodsBreakdown($period);

        return [
            'datasets' => [
                [
                    'label' => 'Cobrado',
                    'data' => $rows->pluck('total_collected')->map(fn (mixed $value): float => (float) $value)->values()->all(),
                    'backgroundColor' => [
                        '#10b981',
                        '#06b6d4',
                        '#3b82f6',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ef4444',
                    ],
                ],
            ],
            'labels' => $rows->map(fn (mixed $row): string => $service->paymentMethodLabel($row->method))->values()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
