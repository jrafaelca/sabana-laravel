<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CreateOrder
{
    public static function execute(array $data): Order
    {
        $data['creator_id'] = Auth::id();
        $data['status'] = OrderStatus::Pending;
        $data['completed_at'] = null;

        return Order::query()->create($data);
    }
}
