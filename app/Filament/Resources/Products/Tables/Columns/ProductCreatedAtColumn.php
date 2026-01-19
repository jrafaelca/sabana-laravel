<?php

namespace App\Filament\Resources\Products\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class ProductCreatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('created_at')
            ->label(trans('filament/resources/product.table.columns.created_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
