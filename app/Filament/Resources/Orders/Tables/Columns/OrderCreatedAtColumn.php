<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderCreatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('created_at')
            ->label(trans('filament/resources/order.table.columns.created_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
