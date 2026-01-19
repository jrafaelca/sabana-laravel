<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductDescriptionColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('description')
            ->label(trans('filament/resources/product.table.columns.description'))
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
