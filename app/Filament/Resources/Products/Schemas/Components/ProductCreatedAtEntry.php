<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class ProductCreatedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('created_at')
            ->label(trans('filament/resources/product.infolist.created_at.label'))
            ->placeholder(trans('filament/resources/product.infolist.created_at.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.created_at.helper_text'))
            ->hint(trans('filament/resources/product.infolist.created_at.hint'))
            ->dateTime();
    }
}
