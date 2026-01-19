<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Forms\Components\TextInput;

class ProductPriceInput
{
    public static function make(): TextInput
    {
        return TextInput::make('price')
            ->label(trans('filament/resources/product.form.fields.price.label'))
            ->placeholder(trans('filament/resources/product.form.fields.price.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.price.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.price.hint'))
            ->required()
            ->numeric()
            ->gte('cost')
            ->default(0);
    }
}
