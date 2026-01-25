<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;

class OrderProductRepeater
{
    public static function make(): Repeater
    {
        return Repeater::make('orderProducts')
            ->label(trans('filament/resources/order.form.fields.order_products.label'))
            ->relationship()
            ->table([
                TableColumn::make(trans('filament/resources/order.table.columns.product')),
                TableColumn::make(trans('filament/resources/order.table.columns.quantity')),
                TableColumn::make(trans('filament/resources/order.table.columns.price')),
                TableColumn::make(trans('filament/resources/order.table.columns.total')),
            ])
            ->schema([
                OrderProductSelect::make(),
                OrderProductQuantityInput::make(),
                OrderProductPriceInput::make(),
                OrderProductTotalInput::make(),
            ])
            ->columns(3)
            ->columnSpanFull();
    }
}
