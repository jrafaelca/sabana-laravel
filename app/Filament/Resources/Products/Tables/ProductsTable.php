<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\Products\Tables\Columns\ProductCostColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductCreatedAtColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductDeletedAtColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductNameColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductPriceColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductSlugColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductStatusColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductUpdatedAtColumn;
use App\Filament\Resources\Products\Tables\Filters\ProductStatusFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ProductStatusColumn::make(),
                ProductNameColumn::make(),
                ProductSlugColumn::make(),
                ProductCostColumn::make(),
                ProductPriceColumn::make(),
                ProductCreatedAtColumn::make(),
                ProductUpdatedAtColumn::make(),
                ProductCreatedAtColumn::make(),
                ProductDeletedAtColumn::make(),
            ])
            ->filters([
                ProductStatusFilter::make(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->modalWidth('lg'),
                    EditAction::make()->modalWidth('lg'),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
