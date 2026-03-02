<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

class ValidateOrderCompletionPayments
{
    /**
     * @throws ValidationException
     */
    public static function execute(Order $order, array $data): void
    {
        if (data_get($data, 'status') !== OrderStatus::Completed->value) {
            return;
        }

        $updatedOrderTotal = array_key_exists('orderProducts', $data)
            ? CalculateOrderTotal::execute((array) data_get($data, 'orderProducts', []))
            : CalculateOrderTotal::forOrder($order);

        $paidAmount = (float) $order->payments()->sum('amount');

        if ($paidAmount + 0.00001 >= $updatedOrderTotal) {
            return;
        }

        throw ValidationException::withMessages([
            'status' => trans('filament/resources/order.form.fields.status.validation.completed_requires_full_payment'),
        ]);
    }
}
