<?php

namespace App\Filament\Resources\Products\Tables\Filters;

use App\Enums\ProductStatus;
use Filament\Tables\Filters\SelectFilter;

class ProductStatusFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('status')
            ->label(trans('filament/resources/product.table.filters.status.label'))
            ->options(ProductStatus::class)
            ->searchable();
    }
}
