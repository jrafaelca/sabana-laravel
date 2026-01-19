<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductNameColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('name')
            ->label(trans('filament/resources/product.table.columns.name'))
            ->grow()
            ->searchable()
            ->sortable()
            ->toggleable();
    }
}
