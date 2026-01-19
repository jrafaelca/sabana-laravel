<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductStatusEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('status')
            ->label(trans('filament/resources/product.infolist.status.label'))
            ->placeholder(trans('filament/resources/product.infolist.status.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.status.helper_text'))
            ->hint(trans('filament/resources/product.infolist.status.hint'))
            ->badge();
    }
}
