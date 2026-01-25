<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;

class OrderStatusSelect
{
    public static function make(): Select
    {
        return Select::make('status')
            ->label(trans('filament/resources/order.form.fields.status.label'))
            ->placeholder(trans('filament/resources/order.form.fields.status.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.status.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.status.hint'))
            ->required()
            ->options(OrderStatus::class)
            ->searchable()
            ->default(OrderStatus::Pending);
    }
}
