<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class OrderItemProductSelect
{
    public static function make(): Select
    {
        return Select::make('product_id')
            ->label(trans('filament/resources/order.form.fields.product.label'))
            ->placeholder(trans('filament/resources/order.form.fields.product.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.product.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.product.hint'))
            ->relationship(
                name: 'product',
                titleAttribute: 'name',
                modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'),
            )
            ->required()
            ->searchable()
            ->preload()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                if (! $state) {
                    $set('unit_price', null);
                    $set('total_price', null);
                    return;
                }

                $price = Product::query()
                    ->whereKey($state)
                    ->value('price');

                $quantity = (int) ($get('quantity') ?? 1);

                $set('unit_price', $price);
                $set('total_price', $price * $quantity);
            })
            ->disableOptionsWhenSelectedInSiblingRepeaterItems();
    }
}
