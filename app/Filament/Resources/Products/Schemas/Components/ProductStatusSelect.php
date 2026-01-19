<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use App\Enums\ProductStatus;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;

class ProductStatusSelect
{
    public static function make(): Select
    {
        return Select::make('status')
            ->label(trans('filament/resources/product.form.fields.status.label'))
            ->placeholder(trans('filament/resources/product.form.fields.status.placeholder'))
            ->helperText(trans('filament/resources/product.form.fields.status.helper_text'))
            ->hint(trans('filament/resources/product.form.fields.status.hint'))
            ->hintIcon(Heroicon::OutlinedQuestionMarkCircle, tooltip: trans('filament/resources/product.form.fields.status.hint_tooltip'))
            ->required()
            ->options(ProductStatus::class)
            ->searchable()
            ->default(ProductStatus::Enabled);
    }
}
