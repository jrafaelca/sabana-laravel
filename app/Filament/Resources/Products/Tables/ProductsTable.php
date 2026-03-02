<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatus;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label(trans('filament/resources/product.table.columns.status'))
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('name')
                    ->label(trans('filament/resources/product.table.columns.name'))
                    ->grow()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->label(trans('filament/resources/product.table.columns.slug'))
                    ->grow()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cost')
                    ->label(trans('filament/resources/product.table.columns.cost'))
                    ->money()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label(trans('filament/resources/product.table.columns.price'))
                    ->money()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(trans('filament/resources/product.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(trans('filament/resources/product.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(trans('filament/resources/product.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(trans('filament/resources/product.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(trans('filament/resources/product.table.filters.status.label'))
                    ->options(ProductStatus::class)
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->modalWidth('lg'),
                    EditAction::make()->modalWidth('lg'),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ]),
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
