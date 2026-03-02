<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\Orders\ValidateOrderCompletionPayments;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('processOrder')
                ->label(trans('filament/resources/order.page.actions.process_order.label'))
                ->icon(Heroicon::OutlinedPlay)
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (Order $record): bool => $record->status === OrderStatus::Pending)
                ->action(function (Order $record): void {
                    $record->update([
                        'status' => OrderStatus::InProgress,
                    ]);

                    Notification::make()
                        ->title(trans('filament/resources/order.page.actions.process_order.success'))
                        ->success()
                        ->send();
                }),
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
            EditAction::make()
                ->visible(fn (Order $record): bool => $record->status !== OrderStatus::Completed),
        ];
    }
}
