<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderCompletedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('completed_at')
            ->label(trans('filament/resources/order.table.columns.completed_at'))
            ->dateTime()
            ->sortable()
            ->toggleable();
    }
}
