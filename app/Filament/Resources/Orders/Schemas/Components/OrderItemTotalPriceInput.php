<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderItemTotalPriceInput
{
    public static function make(): TextInput
    {
        return TextInput::make('total_price')
            ->label(trans('filament/resources/order.form.fields.total_price.label'))
            ->placeholder(trans('filament/resources/order.form.fields.total_price.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.total_price.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.total_price.hint'))
            ->numeric()
            ->disabled()
            ->default(0)
            ->dehydrated();
    }
}
