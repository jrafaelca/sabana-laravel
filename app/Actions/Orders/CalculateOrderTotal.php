<?php

namespace App\Actions\Orders;

use App\Models\Order;

class CalculateOrderTotal
{
    /**
     * @param  array<int, array<string, mixed>>  $orderProducts
     */
    public static function execute(array $orderProducts): float
    {
        return (float) collect($orderProducts)
            ->sum(fn (mixed $orderProduct): float => (float) data_get($orderProduct, 'total_price', 0));
    }

    public static function forOrder(Order $order): float
    {
        return (float) $order->orderProducts()->sum('total_price');
    }
}
