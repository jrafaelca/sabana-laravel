<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductDeletedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('deleted_at')
            ->label(trans('filament/resources/product.table.columns.deleted_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
