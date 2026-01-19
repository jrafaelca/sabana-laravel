<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Forms\Components\TextInput;

class ProductCostInput
{
    public static function make(): TextInput
    {
        return TextInput::make('cost')
            ->label(trans('filament/resources/product.form.fields.cost.label'))
            ->placeholder(trans('filament/resources/product.form.fields.cost.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.cost.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.cost.hint'))
            ->numeric();
    }
}
