<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(trans('filament/resources/order.tabs.all')),
            OrderStatus::Pending->value => Tab::make(trans('order.status.pending'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', OrderStatus::Pending)),
            OrderStatus::InProgress->value => Tab::make(trans('order.status.in_progress'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', OrderStatus::InProgress)),
            OrderStatus::Completed->value => Tab::make(trans('order.status.completed'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', OrderStatus::Completed)),
            OrderStatus::Cancelled->value => Tab::make(trans('order.status.cancelled'))
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', OrderStatus::Cancelled)),
        ];
    }
}
