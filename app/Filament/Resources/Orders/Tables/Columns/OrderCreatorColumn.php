<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderCreatorColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('creator.name')
            ->label(trans('filament/resources/order.table.columns.creator'))
            ->sortable()
            ->toggleable();
    }
}
