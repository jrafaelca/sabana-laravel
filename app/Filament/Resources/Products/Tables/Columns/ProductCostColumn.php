<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductCostColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('cost')
            ->label(trans('filament/resources/product.table.columns.cost'))
            ->money()
            ->sortable()
            ->toggleable();
    }
}
