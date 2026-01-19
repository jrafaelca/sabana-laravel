<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class ProductNameInput
{
    public static function make(): TextInput
    {
        return TextInput::make('name')
            ->label(trans('filament/resources/product.form.fields.name.label'))
            ->placeholder(trans('filament/resources/product.form.fields.name.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.name.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.name.hint'))
            ->required()
            ->minLength(2)
            ->maxLength(190)
            ->live(true)
            ->afterStateUpdated(fn(Set $set, $state) => $set('slug', Str::slug($state)));
    }
}
