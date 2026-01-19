<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductNameEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('name')
            ->label(trans('filament/resources/product.infolist.name.label'))
            ->placeholder(trans('filament/resources/product.infolist.name.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.name.helper_text'))
            ->hint(trans('filament/resources/product.infolist.name.hint'));
    }
}
