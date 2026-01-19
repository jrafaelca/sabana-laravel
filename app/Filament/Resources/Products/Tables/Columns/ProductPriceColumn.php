<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductPriceColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('price')
            ->label(trans('filament/resources/product.table.columns.price'))
            ->money()
            ->sortable()
            ->toggleable();
    }
}
