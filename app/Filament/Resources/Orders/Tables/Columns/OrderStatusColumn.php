<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderStatusColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('status')
            ->label(trans('filament/resources/order.table.columns.status'))
            ->badge()
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
