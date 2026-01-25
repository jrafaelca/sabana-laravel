<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderServerColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('server.name')
            ->label(trans('filament/resources/order.table.columns.server'))
            ->sortable()
            ->toggleable();
    }
}
