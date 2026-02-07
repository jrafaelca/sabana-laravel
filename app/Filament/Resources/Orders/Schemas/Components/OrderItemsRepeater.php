<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;

class OrderItemsRepeater
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
                OrderItemProductSelect::make(),
                OrderItemQuantityInput::make(),
                OrderItemUnitPriceInput::make(),
                OrderItemTotalPriceInput::make(),
            ])
            ->columns(3)
            ->columnSpanFull();
    }
}
