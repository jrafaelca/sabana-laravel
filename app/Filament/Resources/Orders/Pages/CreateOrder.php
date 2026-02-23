<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\CalculateOrderTotalAction;
use App\Actions\CreateOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total'] = CalculateOrderTotalAction::handle($data['orderProducts'] ?? []);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return CreateOrderAction::handle($data);
    }
}
