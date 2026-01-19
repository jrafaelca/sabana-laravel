<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Forms\Components\Textarea;

class ProductDescriptionTextarea
{
    public static function make(): Textarea
    {
        return Textarea::make('description')
            ->label(trans('filament/resources/product.form.fields.description.label'))
            ->placeholder(trans('filament/resources/product.form.fields.description.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.description.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.description.hint'))
            ->minLength(2)
            ->maxLength(190)
            ->autosize();
    }
}
