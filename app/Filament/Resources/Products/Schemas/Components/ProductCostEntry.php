<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductCostEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('cost')
            ->label(trans('filament/resources/product.infolist.cost.label'))
            ->placeholder(trans('filament/resources/product.infolist.cost.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.cost.helper_text'))
            ->hint(trans('filament/resources/product.infolist.cost.hint'))
            ->money();
    }
}
