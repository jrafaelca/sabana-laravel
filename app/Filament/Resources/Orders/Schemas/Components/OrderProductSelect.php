<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class OrderProductSelect
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
                    $set('price', null);
                    $set('total', null);
                    return;
                }

                $price = Product::query()
                    ->whereKey($state)
                    ->value('price');

                $quantity = (int) ($get('quantity') ?? 1);

                $set('price', $price);
                $set('total', $price * $quantity);
            })
            ->disableOptionsWhenSelectedInSiblingRepeaterItems();
    }
}
