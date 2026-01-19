<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductPriceEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('price')
            ->label(trans('filament/resources/product.infolist.price.label'))
            ->placeholder(trans('filament/resources/product.infolist.price.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.price.helper_text'))
            ->hint(trans('filament/resources/product.infolist.price.hint'))
            ->money();
    }
}
