<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderProductQuantityInput
{
    public static function make(): TextInput
    {
        return TextInput::make('quantity')
            ->label(trans('filament/resources/order.form.fields.quantity.label'))
            ->placeholder(trans('filament/resources/order.form.fields.quantity.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.quantity.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.quantity.hint'))
            ->numeric()
            ->default(1)
            ->minValue(1)
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                $price = (float)($get('price') ?? 0);
                $quantity = (int)($state ?? 0);

                $set('total', $price * $quantity);
            })
            ->required();
    }
}
