<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;

class OrderGrandTotalPlaceholder
{
    public static function make(): Placeholder
    {
        return Placeholder::make('grand_total')
            ->label(trans('filament/resources/order.form.fields.grand_total.label'))
            ->content(function (Get $get): string {
                $orderProducts = $get('orderProducts');

                if (! is_array($orderProducts)) {
                    return '$0.00';
                }

                $grandTotal = collect($orderProducts)
                    ->sum(fn (mixed $orderProduct): float => (float) data_get($orderProduct, 'total_price', 0));

                return '$'.number_format($grandTotal, 2, '.', ',');
            });
    }
}
