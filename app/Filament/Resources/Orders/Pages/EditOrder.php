<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\Orders\CalculateOrderTotal;
use App\Actions\Orders\ValidateOrderCompletionPayments;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['status'], $data['completed_at']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->update([
            'total' => CalculateOrderTotal::forOrder($this->record),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('completeOrder')
                ->label(trans('filament/resources/order.page.actions.complete_order.label'))
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Order $record): bool => $record->status === OrderStatus::InProgress)
                ->action(function (Order $record, Action $action) {
                    try {
                        ValidateOrderCompletionPayments::execute($record, [
                            'status' => OrderStatus::Completed->value,
                        ]);
                    } catch (ValidationException $exception) {
                        $message = (string) collect($exception->errors())->flatten()->first();

                        Notification::make()
                            ->title($message)
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    $record->update([
                        'status' => OrderStatus::Completed,
                        'completed_at' => now(),
                        'server_id' => Auth::id(),
                    ]);

                    Notification::make()
                        ->title(trans('filament/resources/order.page.actions.complete_order.success'))
                        ->success()
                        ->send();

                    return redirect(OrderResource::getUrl('index'));
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
