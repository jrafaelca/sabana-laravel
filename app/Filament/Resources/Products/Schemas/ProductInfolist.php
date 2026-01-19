<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Resources\Products\Schemas\Components\ProductCostEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductCreatedAtEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductDeletedAtEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductDescriptionEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductNameEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductPriceEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductSlugEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductStatusEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductUpdatedAtEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ProductStatusEntry::make(),
                ProductNameEntry::make(),
                ProductSlugEntry::make(),
                ProductDescriptionEntry::make(),
                ProductCostEntry::make(),
                ProductPriceEntry::make(),
                ProductCreatedAtEntry::make(),
                ProductUpdatedAtEntry::make(),
                ProductDeletedAtEntry::make(),
            ]);
    }
}
