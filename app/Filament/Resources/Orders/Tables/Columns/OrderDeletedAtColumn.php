<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderDeletedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('deleted_at')
            ->label(trans('filament/resources/order.table.columns.deleted_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
