<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    public static function handle(array $data): Order
    {
        $data['creator_id'] = Auth::id();

        return Order::query()->create($data);
    }
}
