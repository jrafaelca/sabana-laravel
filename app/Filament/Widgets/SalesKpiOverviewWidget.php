<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\OrderMetricsService;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesKpiOverviewWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Resumen Ejecutivo';

    protected function getStats(): array
    {
        $service = app(OrderMetricsService::class);
        $period = data_get($this->pageFilters, 'period');
        $kpis = $service->getKpis($period);

        return [
            $this->moneyStat('Ventas cerradas', $kpis['metrics']['sales']),
            $this->countStat('Productos vendidos', $kpis['metrics']['products_sold']),
            $this->countStat('Órdenes completadas', $kpis['metrics']['completed_orders']),
            $this->moneyStat('Ticket promedio', $kpis['metrics']['average_ticket']),
            $this->moneyStat('Utilidad bruta', $kpis['metrics']['gross_profit']),
            $this->percentageStat('Margen bruto', $kpis['metrics']['gross_margin_percentage']),
            $this->moneyStat('Cobrado', $kpis['metrics']['collected']),
            $this->percentageStat('Tasa de cobro', $kpis['metrics']['collection_rate_percentage']),
        ];
    }

    /**
     * @param  array{current: float, previous: float, delta: float, delta_percentage: float|null}  $metric
     */
    private function moneyStat(string $label, array $metric): Stat
    {
        return Stat::make($label, $this->money($metric['current']))
            ->description($this->buildDescription($metric, true))
            ->descriptionIcon($this->trendIcon($metric['delta']))
            ->descriptionColor($this->trendColor($metric['delta']))
            ->chartColor($this->trendColor($metric['delta']));
    }

    /**
     * @param  array{current: float, previous: float, delta: float, delta_percentage: float|null}  $metric
     */
    private function countStat(string $label, array $metric): Stat
    {
        return Stat::make($label, number_format($metric['current'], 0, '.', ','))
            ->description($this->buildDescription($metric, false))
            ->descriptionIcon($this->trendIcon($metric['delta']))
            ->descriptionColor($this->trendColor($metric['delta']))
            ->chartColor($this->trendColor($metric['delta']));
    }

    /**
     * @param  array{current: float, previous: float, delta: float, delta_percentage: float|null}  $metric
     */
    private function percentageStat(string $label, array $metric): Stat
    {
        return Stat::make($label, $this->percentage($metric['current']))
            ->description($this->buildDescription($metric, false, true))
            ->descriptionIcon($this->trendIcon($metric['delta']))
            ->descriptionColor($this->trendColor($metric['delta']))
            ->chartColor($this->trendColor($metric['delta']));
    }

    /**
     * @param  array{current: float, previous: float, delta: float, delta_percentage: float|null}  $metric
     */
    private function buildDescription(array $metric, bool $asMoney = false, bool $asPercentage = false): string
    {
        $deltaValue = $asMoney
            ? $this->money($metric['delta'])
            : ($asPercentage ? $this->percentage($metric['delta']) : number_format($metric['delta'], 0, '.', ','));

        $deltaPercentage = $metric['delta_percentage'] === null
            ? 'N/A'
            : $this->percentage($metric['delta_percentage']);

        return sprintf('%s (%s) vs periodo anterior', $deltaValue, $deltaPercentage);
    }

    private function money(float $value): string
    {
        return '$'.number_format($value, 2, '.', ',');
    }

    private function percentage(float $value): string
    {
        return number_format($value, 2, '.', ',').'%';
    }

    private function trendIcon(float $delta): string|BackedEnum
    {
        if ($delta > 0) {
            return Heroicon::OutlinedArrowTrendingUp;
        }

        if ($delta < 0) {
            return Heroicon::OutlinedArrowTrendingDown;
        }

        return Heroicon::OutlinedMinus;
    }

    private function trendColor(float $delta): string
    {
        if ($delta > 0) {
            return 'success';
        }

        if ($delta < 0) {
            return 'danger';
        }

        return 'gray';
    }
}
