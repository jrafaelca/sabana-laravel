<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\Orders\CalculateOrderTotal;
use App\Actions\Orders\CreateOrder as CreateOrderAction;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $this->record->update([
            'total' => CalculateOrderTotal::forOrder($this->record),
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return CreateOrderAction::execute($data);
    }
}
