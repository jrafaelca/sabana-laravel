<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Orders\Tables\Columns\Filters\OrderStatusFilter;
use App\Filament\Resources\Orders\Tables\Columns\OrderCompletedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderCreatedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderCreatorColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderDeletedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderNumberColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderServerColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderStatusColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderUpdatedAtColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                OrderNumberColumn::make(),
                OrderStatusColumn::make(),
                OrderCreatorColumn::make(),
                OrderServerColumn::make(),
                OrderCompletedAtColumn::make(),
                OrderCreatedAtColumn::make(),
                OrderUpdatedAtColumn::make(),
                OrderDeletedAtColumn::make(),
            ])
            ->filters([
                OrderStatusFilter::make(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
