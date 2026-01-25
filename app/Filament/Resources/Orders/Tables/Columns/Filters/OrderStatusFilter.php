<?php

namespace App\Filament\Resources\Orders\Tables\Columns\Filters;

use App\Enums\OrderStatus;
use Filament\Tables\Filters\SelectFilter;

class OrderStatusFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('status')
            ->label(trans('filament/resources/order.table.filters.status.label'))
            ->options(OrderStatus::class)
            ->searchable();
    }
}
