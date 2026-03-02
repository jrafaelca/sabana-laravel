<?php

namespace App\Services\Dashboard;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderMetricsService
{
    public const PERIOD_DAY = 'day';

    public const PERIOD_WEEK = 'week';

    public const PERIOD_MONTH = 'month';

    public const PERIOD_QUARTER = 'quarter';

    /**
     * @return array<string, string>
     */
    public static function periodOptions(): array
    {
        return [
            self::PERIOD_DAY => 'Hoy',
            self::PERIOD_WEEK => 'Semana',
            self::PERIOD_MONTH => 'Mes',
            self::PERIOD_QUARTER => 'Trimestre',
        ];
    }

    /**
     * @return array{
     *     period: string,
     *     current_start: CarbonImmutable,
     *     current_end: CarbonImmutable,
     *     previous_start: CarbonImmutable,
     *     previous_end: CarbonImmutable
     * }
     */
    public function getRanges(?string $period = null, CarbonInterface|string|null $now = null): array
    {
        $resolvedPeriod = $this->resolvePeriod($period);
        $currentEnd = CarbonImmutable::parse($now ?? now(), config('app.timezone'));
        $currentStart = match ($resolvedPeriod) {
            self::PERIOD_DAY => $currentEnd->startOfDay(),
            self::PERIOD_WEEK => $currentEnd->startOfWeek(),
            self::PERIOD_QUARTER => $currentEnd->startOfQuarter(),
            default => $currentEnd->startOfMonth(),
        };

        $durationInSeconds = max(0, $currentStart->diffInSeconds($currentEnd));
        $previousStart = match ($resolvedPeriod) {
            self::PERIOD_DAY => $currentStart->subDay(),
            self::PERIOD_WEEK => $currentStart->subWeek(),
            self::PERIOD_QUARTER => $currentStart->subQuarter(),
            default => $currentStart->subMonth(),
        };
        $previousPeriodEnd = match ($resolvedPeriod) {
            self::PERIOD_DAY => $previousStart->endOfDay(),
            self::PERIOD_WEEK => $previousStart->endOfWeek(),
            self::PERIOD_QUARTER => $previousStart->endOfQuarter(),
            default => $previousStart->endOfMonth(),
        };
        $previousEnd = $previousStart->addSeconds($durationInSeconds);

        if ($previousEnd->greaterThan($previousPeriodEnd)) {
            $previousEnd = $previousPeriodEnd;
        }

        return [
            'period' => $resolvedPeriod,
            'current_start' => $currentStart,
            'current_end' => $currentEnd,
            'previous_start' => $previousStart,
            'previous_end' => $previousEnd,
        ];
    }

    /**
     * @return array{
     *     ranges: array{period: string, current_start: CarbonImmutable, current_end: CarbonImmutable, previous_start: CarbonImmutable, previous_end: CarbonImmutable},
     *     metrics: array<string, array{current: float, previous: float, delta: float, delta_percentage: float|null}>
     * }
     */
    public function getKpis(?string $period = null, CarbonInterface|string|null $now = null): array
    {
        $ranges = $this->getRanges($period, $now);

        $currentSales = $this->getSales($ranges['current_start'], $ranges['current_end']);
        $previousSales = $this->getSales($ranges['previous_start'], $ranges['previous_end']);

        $currentOrders = $this->getCompletedOrdersCount($ranges['current_start'], $ranges['current_end']);
        $previousOrders = $this->getCompletedOrdersCount($ranges['previous_start'], $ranges['previous_end']);

        $currentProductsSold = $this->getProductsSold($ranges['current_start'], $ranges['current_end']);
        $previousProductsSold = $this->getProductsSold($ranges['previous_start'], $ranges['previous_end']);

        $currentGrossProfit = $this->getGrossProfit($ranges['current_start'], $ranges['current_end']);
        $previousGrossProfit = $this->getGrossProfit($ranges['previous_start'], $ranges['previous_end']);

        $currentCollected = $this->getCollected($ranges['current_start'], $ranges['current_end']);
        $previousCollected = $this->getCollected($ranges['previous_start'], $ranges['previous_end']);

        $currentAverageTicket = $currentOrders > 0 ? $currentSales / $currentOrders : 0;
        $previousAverageTicket = $previousOrders > 0 ? $previousSales / $previousOrders : 0;

        $currentMargin = $currentSales > 0 ? ($currentGrossProfit / $currentSales) * 100 : 0;
        $previousMargin = $previousSales > 0 ? ($previousGrossProfit / $previousSales) * 100 : 0;

        $currentCollectionRate = $currentSales > 0 ? ($currentCollected / $currentSales) * 100 : 0;
        $previousCollectionRate = $previousSales > 0 ? ($previousCollected / $previousSales) * 100 : 0;

        return [
            'ranges' => $ranges,
            'metrics' => [
                'sales' => $this->compare($currentSales, $previousSales),
                'products_sold' => $this->compare($currentProductsSold, $previousProductsSold),
                'completed_orders' => $this->compare($currentOrders, $previousOrders),
                'average_ticket' => $this->compare($currentAverageTicket, $previousAverageTicket),
                'gross_profit' => $this->compare($currentGrossProfit, $previousGrossProfit),
                'gross_margin_percentage' => $this->compare($currentMargin, $previousMargin),
                'collected' => $this->compare($currentCollected, $previousCollected),
                'collection_rate_percentage' => $this->compare($currentCollectionRate, $previousCollectionRate),
            ],
        ];
    }

    /**
     * @return array{labels: array<int, string>, current: array<int, float>, previous: array<int, float>}
     */
    public function getTrendData(?string $period = null, string $metric = 'sales', CarbonInterface|string|null $now = null): array
    {
        $ranges = $this->getRanges($period, $now);

        $currentStartDay = $ranges['current_start']->startOfDay();
        $currentEndDay = $ranges['current_end']->startOfDay();
        $days = $currentStartDay->diffInDays($currentEndDay) + 1;

        $currentValuesByDay = $this->getTrendValuesByDay($metric, $ranges['current_start'], $ranges['current_end']);
        $previousValuesByDay = $this->getTrendValuesByDay($metric, $ranges['previous_start'], $ranges['previous_end']);

        $labels = [];
        $currentSeries = [];
        $previousSeries = [];

        for ($index = 0; $index < $days; $index++) {
            $currentDate = $currentStartDay->addDays($index);
            $previousDate = $ranges['previous_start']->startOfDay()->addDays($index);

            $labels[] = $currentDate->format('d M');
            $currentSeries[] = (float) ($currentValuesByDay[$currentDate->toDateString()] ?? 0);
            $previousSeries[] = (float) ($previousValuesByDay[$previousDate->toDateString()] ?? 0);
        }

        return [
            'labels' => $labels,
            'current' => $currentSeries,
            'previous' => $previousSeries,
        ];
    }

    /**
     * @return Collection<int, object{method: string, total_collected: numeric-string|float|int}>
     */
    public function getPaymentMethodsBreakdown(?string $period = null, CarbonInterface|string|null $now = null): Collection
    {
        $ranges = $this->getRanges($period, $now);

        return Payment::query()
            ->selectRaw('method, COALESCE(SUM(amount), 0) as total_collected')
            ->whereHas('order', function (Builder $query) use ($ranges): void {
                $this->applyCompletedOrderRange($query, $ranges['current_start'], $ranges['current_end']);
            })
            ->groupBy('method')
            ->orderByDesc('total_collected')
            ->get();
    }

    public function getTopProductsQuery(?string $period = null, int $limit = 10): Builder
    {
        $ranges = $this->getRanges($period);

        $previousPeriodSubquery = OrderProduct::query()
            ->selectRaw('order_product.product_id, COALESCE(SUM(order_product.quantity), 0) as previous_units_sold, COALESCE(SUM(order_product.total_price), 0) as previous_sales_total')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', OrderStatus::Completed->value)
            ->whereNotNull('orders.completed_at')
            ->whereBetween('orders.completed_at', [$ranges['previous_start'], $ranges['previous_end']])
            ->groupBy('order_product.product_id');

        return Product::query()
            ->selectRaw('products.id, products.name, COALESCE(SUM(order_product.quantity), 0) as units_sold, COALESCE(SUM(order_product.total_price), 0) as sales_total, COALESCE(SUM(order_product.total_price - (order_product.cost * order_product.quantity)), 0) as gross_profit')
            ->selectRaw('COALESCE(previous_metrics.previous_units_sold, 0) as previous_units_sold, COALESCE(previous_metrics.previous_sales_total, 0) as previous_sales_total')
            ->join('order_product', 'order_product.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->leftJoinSub($previousPeriodSubquery, 'previous_metrics', function ($join): void {
                $join->on('previous_metrics.product_id', '=', 'products.id');
            })
            ->whereNull('orders.deleted_at')
            ->where('orders.status', OrderStatus::Completed->value)
            ->whereNotNull('orders.completed_at')
            ->whereBetween('orders.completed_at', [$ranges['current_start'], $ranges['current_end']])
            ->groupBy('products.id', 'products.name', 'previous_metrics.previous_units_sold', 'previous_metrics.previous_sales_total')
            ->orderByDesc('sales_total')
            ->limit($limit);
    }

    public function resolvePeriod(?string $period): string
    {
        $allowedPeriods = [
            self::PERIOD_DAY,
            self::PERIOD_WEEK,
            self::PERIOD_MONTH,
            self::PERIOD_QUARTER,
        ];

        return in_array($period, $allowedPeriods, true) ? $period : self::PERIOD_MONTH;
    }

    private function getSales(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) Order::query()
            ->where('status', OrderStatus::Completed)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end])
            ->sum('total');
    }

    private function getCompletedOrdersCount(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) Order::query()
            ->where('status', OrderStatus::Completed)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end])
            ->count();
    }

    private function getProductsSold(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) OrderProduct::query()
            ->whereHas('order', function (Builder $query) use ($start, $end): void {
                $this->applyCompletedOrderRange($query, $start, $end);
            })
            ->sum('quantity');
    }

    private function getGrossProfit(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) OrderProduct::query()
            ->selectRaw('COALESCE(SUM(total_price - (cost * quantity)), 0) as gross_profit')
            ->whereHas('order', function (Builder $query) use ($start, $end): void {
                $this->applyCompletedOrderRange($query, $start, $end);
            })
            ->value('gross_profit');
    }

    private function getCollected(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) Payment::query()
            ->whereHas('order', function (Builder $query) use ($start, $end): void {
                $this->applyCompletedOrderRange($query, $start, $end);
            })
            ->sum('amount');
    }

    /**
     * @return array{current: float, previous: float, delta: float, delta_percentage: float|null}
     */
    private function compare(float $current, float $previous): array
    {
        $delta = $current - $previous;

        return [
            'current' => $current,
            'previous' => $previous,
            'delta' => $delta,
            'delta_percentage' => $previous !== 0.0 ? (($delta / $previous) * 100) : null,
        ];
    }

    /**
     * @return Collection<string, float>
     */
    private function getTrendValuesByDay(string $metric, CarbonImmutable $start, CarbonImmutable $end): Collection
    {
        if ($metric === 'gross_profit') {
            return Order::query()
                ->selectRaw('DATE(orders.completed_at) as period_date')
                ->selectRaw('COALESCE(SUM(order_product.total_price - (order_product.cost * order_product.quantity)), 0) as value')
                ->join('order_product', 'order_product.order_id', '=', 'orders.id')
                ->where('orders.status', OrderStatus::Completed->value)
                ->whereNull('orders.deleted_at')
                ->whereNotNull('orders.completed_at')
                ->whereBetween('orders.completed_at', [$start, $end])
                ->groupByRaw('DATE(orders.completed_at)')
                ->pluck('value', 'period_date')
                ->map(fn (mixed $value): float => (float) $value);
        }

        return Order::query()
            ->selectRaw('DATE(completed_at) as period_date')
            ->selectRaw('COALESCE(SUM(total), 0) as value')
            ->where('status', OrderStatus::Completed)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end])
            ->groupByRaw('DATE(completed_at)')
            ->pluck('value', 'period_date')
            ->map(fn (mixed $value): float => (float) $value);
    }

    private function applyCompletedOrderRange(Builder $query, CarbonImmutable $start, CarbonImmutable $end): void
    {
        $query
            ->where('status', OrderStatus::Completed)
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$start, $end]);
    }

    public function paymentMethodLabel(PaymentMethods|string $method): string
    {
        if ($method instanceof PaymentMethods) {
            return (string) $method->getLabel();
        }

        $methodEnum = PaymentMethods::tryFrom($method);

        if ($methodEnum === null) {
            return str($method)
                ->replace('_', ' ')
                ->title()
                ->value();
        }

        return (string) $methodEnum->getLabel();
    }
}
