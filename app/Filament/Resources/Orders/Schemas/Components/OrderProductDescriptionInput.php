<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\TextInput;

class OrderProductDescriptionInput
{
    public static function make(): TextInput
    {
        return TextInput::make('description')
            ->label(trans('filament/resources/order.form.fields.description.label'))
            ->placeholder(trans('filament/resources/order.form.fields.description.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.description.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.description.hint'))
            ->minLength(2)
            ->maxLength(190);
    }
}
