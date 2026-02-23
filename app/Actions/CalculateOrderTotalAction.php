<?php

namespace App\Actions;

use App\Models\Order;

class CalculateOrderTotalAction
{
    /**
     * @param  array<int, array<string, mixed>>  $orderProducts
     */
    public static function handle(array $orderProducts): float
    {
        return (float) collect($orderProducts)
            ->sum(fn (mixed $orderProduct): float => (float) data_get($orderProduct, 'total_price', 0));
    }

    public static function forOrder(Order $order): float
    {
        return (float) $order->orderProducts()->sum('total_price');
    }
}
