<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Actions\Orders\ValidateOrderCompletionPayments;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(trans('filament/resources/order.table.columns.number'))
                    ->badge()
                    ->numeric()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('creator.name')
                    ->label(trans('filament/resources/order.table.columns.creator'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('server.name')
                    ->label(trans('filament/resources/order.table.columns.server'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label(trans('filament/resources/order.table.columns.status'))
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total')
                    ->label(trans('filament/resources/order.table.columns.total'))
                    ->money()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('pending_balance')
                    ->label(trans('filament/resources/order.table.columns.pending_balance'))
                    ->state(function (Order $record): float {
                        $paidAmount = (float) ($record->paid_amount ?? 0);
                        $orderTotal = (float) ($record->total ?? 0);

                        return max(0, round($orderTotal - $paidAmount, 2));
                    })
                    ->money()
                    ->toggleable(),
                TextColumn::make('completed_at')
                    ->label(trans('filament/resources/order.table.columns.completed_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(trans('filament/resources/order.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label(trans('filament/resources/order.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(trans('filament/resources/order.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(trans('filament/resources/order.table.filters.status.label'))
                    ->options(OrderStatus::class)
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('processOrder')
                    ->label(trans('filament/resources/order.table.actions.process_order.label'))
                    ->icon(Heroicon::OutlinedPlay)
                    ->color('warning')
                    ->visible(fn (Order $record): bool => $record->status === OrderStatus::Pending)
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => OrderStatus::InProgress,
                        ]);

                        return redirect(OrderResource::getUrl('view', ['record' => $record]));
                    }),
                Action::make('completeOrder')
                    ->label(trans('filament/resources/order.table.actions.complete_order.label'))
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool => $record->status === OrderStatus::InProgress)
                    ->action(function (Order $record, Action $action): void {
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
                            ->title(trans('filament/resources/order.table.actions.complete_order.success'))
                            ->success()
                            ->send();
                    }),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn (Order $record): bool => $record->status !== OrderStatus::Completed),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withSum('payments as paid_amount', 'amount'))
            ->defaultSort('created_at', 'desc');
    }
}
