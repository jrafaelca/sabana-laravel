<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductUpdatedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('updated_at')
            ->label(trans('filament/resources/product.infolist.updated_at.label'))
            ->placeholder(trans('filament/resources/product.infolist.updated_at.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.updated_at.helper_text'))
            ->hint(trans('filament/resources/product.infolist.updated_at.hint'))
            ->dateTime();
    }
}
