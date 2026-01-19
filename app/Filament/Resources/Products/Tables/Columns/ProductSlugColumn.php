<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductSlugColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('slug')
            ->label(trans('filament/resources/product.table.columns.slug'))
            ->grow()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
