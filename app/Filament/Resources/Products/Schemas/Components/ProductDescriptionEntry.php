<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductDescriptionEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('description')
            ->label(trans('filament/resources/product.infolist.description.label'))
            ->placeholder(trans('filament/resources/product.infolist.description.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.description.helper_text'))
            ->hint(trans('filament/resources/product.infolist.description.hint'));
    }
}
