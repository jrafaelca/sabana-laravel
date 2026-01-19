<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductSlugEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('slug')
            ->label(trans('filament/resources/product.infolist.slug.label'))
            ->placeholder(trans('filament/resources/product.infolist.slug.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.slug.helper_text'))
            ->hint(trans('filament/resources/product.infolist.slug.hint'));
    }
}
