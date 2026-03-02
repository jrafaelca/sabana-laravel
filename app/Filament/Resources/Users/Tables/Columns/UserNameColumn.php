<?php

namespace App\Filament\Resources\Users\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class UserNameColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('name')
            ->label(trans('filament/resources/user.table.columns.name'))
            ->grow()
            ->searchable()
            ->sortable()
            ->toggleable();
    }
}
