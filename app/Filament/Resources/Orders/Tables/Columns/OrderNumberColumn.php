<?php

namespace App\Filament\Resources\Orders\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class OrderNumberColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('id')
            ->label(trans('filament/resources/order.table.columns.number'))
            ->badge()
            ->numeric()
            ->searchable()
            ->sortable()
            ->toggleable();
    }
}
