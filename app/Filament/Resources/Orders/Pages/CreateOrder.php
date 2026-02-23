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

    protected function afterCreate(): void
    {
        $this->record->update([
            'total' => CalculateOrderTotalAction::forOrder($this->record),
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return CreateOrderAction::handle($data);
    }
}
