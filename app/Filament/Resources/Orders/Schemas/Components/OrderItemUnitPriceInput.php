<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderItemUnitPriceInput
{
    public static function make(): TextInput
    {
        return TextInput::make('unit_price')
            ->label(trans('filament/resources/order.form.fields.unit_price.label'))
            ->placeholder(trans('filament/resources/order.form.fields.unit_price.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.unit_price.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.unit_price.hint'))
            ->numeric()
            ->disabled()
            ->dehydrated();
    }
}
