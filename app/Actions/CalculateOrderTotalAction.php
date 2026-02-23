<?php

namespace App\Actions;

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
}
