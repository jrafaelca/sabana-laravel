<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderUpdatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(trans('filament/resources/order.table.columns.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
