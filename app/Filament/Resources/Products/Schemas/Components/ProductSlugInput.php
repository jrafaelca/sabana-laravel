<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use App\Models\Product;
use Filament\Forms\Components\TextInput;

class ProductSlugInput
{
    public static function make(): TextInput
    {
        return TextInput::make('slug')
            ->label(trans('filament/resources/product.form.fields.slug.label'))
            ->placeholder(trans('filament/resources/product.form.fields.slug.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.slug.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.slug.hint'))
            ->required()
            ->minLength(2)
            ->maxLength(190)
            ->unique(Product::class, 'slug', ignoreRecord: true);
    }
}
