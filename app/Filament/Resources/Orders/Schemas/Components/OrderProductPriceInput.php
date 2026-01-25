<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderProductPriceInput
{
    public static function make(): TextInput
    {
        return TextInput::make('price')
            ->label(trans('filament/resources/order.form.fields.price.label'))
            ->placeholder(trans('filament/resources/order.form.fields.price.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.price.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.price.hint'))
            ->numeric()
            ->disabled()
            ->dehydrated() ;
    }
}
