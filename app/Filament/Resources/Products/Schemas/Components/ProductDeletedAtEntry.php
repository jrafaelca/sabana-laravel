<?php

namespace App\Filament\Resources\Products\Schemas\Components;

use App\Models\Product;
use Filament\Infolists\Components\TextEntry;

class ProductDeletedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('deleted_at')
            ->label(trans('filament/resources/product.infolist.deleted_at.label'))
            ->placeholder(trans('filament/resources/product.infolist.deleted_at.placeholder'))
            ->helperText(trans('filament/resources/product.infolist.deleted_at.helper_text'))
            ->hint(trans('filament/resources/product.infolist.deleted_at.hint'))
            ->dateTime()
            ->visible(fn (Product $record): bool => $record->trashed());
    }
}
