<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductUpdatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(trans('filament/resources/product.table.columns.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
