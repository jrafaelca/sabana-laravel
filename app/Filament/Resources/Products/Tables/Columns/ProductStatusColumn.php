<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductStatusColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('status')
            ->label(trans('filament/resources/product.table.columns.status'))
            ->badge()
            ->sortable()
            ->toggleable();
    }
}
