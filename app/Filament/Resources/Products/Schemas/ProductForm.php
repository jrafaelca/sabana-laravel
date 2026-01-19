<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Resources\Products\Schemas\Components\ProductCostInput;
use App\Filament\Resources\Products\Schemas\Components\ProductDescriptionTextarea;
use App\Filament\Resources\Products\Schemas\Components\ProductNameInput;
use App\Filament\Resources\Products\Schemas\Components\ProductPriceInput;
use App\Filament\Resources\Products\Schemas\Components\ProductSlugInput;
use App\Filament\Resources\Products\Schemas\Components\ProductStatusSelect;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ProductNameInput::make(),
                ProductSlugInput::make(),
                ProductDescriptionTextarea::make(),
                ProductCostInput::make(),
                ProductPriceInput::make(),
                ProductStatusSelect::make(),
            ]);
    }
}
