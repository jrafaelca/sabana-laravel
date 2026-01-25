<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderProductTotalInput
{
    public static function make(): TextInput
    {
        return TextInput::make('total')
            ->label(trans('filament/resources/order.form.fields.total.label'))
            ->placeholder(trans('filament/resources/order.form.fields.total.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.total.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.total.hint'))
            ->numeric()
            ->disabled()
            ->default(0)
            ->dehydrated();
    }
}
