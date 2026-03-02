<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Actions\Payments\CreatePayment;
use App\Enums\PaymentMethods;
use App\Models\Payment;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\ValidationException;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament/resources/payment.label');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('reference')
                    ->label(trans('filament/resources/payment.form.fields.reference.label'))
                    ->placeholder(trans('filament/resources/payment.form.fields.reference.placeholder'))
                    ->helperText(trans('filament/resources/payment.form.fields.reference.helper_text'))
                    ->hint(trans('filament/resources/payment.form.fields.reference.hint')),
                Select::make('method')
                    ->label(trans('filament/resources/payment.form.fields.method.label'))
                    ->placeholder(trans('filament/resources/payment.form.fields.method.placeholder'))
                    ->helperText(trans('filament/resources/payment.form.fields.method.helper_text'))
                    ->hint(trans('filament/resources/payment.form.fields.method.hint'))
                    ->required()
                    ->options(PaymentMethods::class)
                    ->searchable(),
                TextInput::make('amount')
                    ->label(trans('filament/resources/payment.form.fields.amount.label'))
                    ->placeholder(trans('filament/resources/payment.form.fields.amount.placeholder'))
                    ->helperText(trans('filament/resources/payment.form.fields.amount.helper_text'))
                    ->hint(trans('filament/resources/payment.form.fields.amount.hint'))
                    ->default(fn (): float => $this->getPendingBalanceAmount())
                    ->afterStateHydrated(function (TextInput $component, mixed $state): void {
                        if (blank($state)) {
                            $component->state($this->getPendingBalanceAmount());
                        }
                    })
                    ->required()
                    ->numeric(),
                Textarea::make('note')
                    ->label(trans('filament/resources/payment.form.fields.note.label'))
                    ->placeholder(trans('filament/resources/payment.form.fields.note.placeholder'))
                    ->helperText(trans('filament/resources/payment.form.fields.note.helper_text'))
                    ->hint(trans('filament/resources/payment.form.fields.note.hint')),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextEntry::make('reference')
                    ->label(trans('filament/resources/payment.infolist.reference.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.reference.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.reference.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.reference.hint')),
                TextEntry::make('method')
                    ->label(trans('filament/resources/payment.infolist.method.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.method.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.method.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.method.hint'))
                    ->badge(),
                TextEntry::make('amount')
                    ->label(trans('filament/resources/payment.infolist.amount.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.amount.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.amount.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.amount.hint'))
                    ->money(),
                TextEntry::make('note')
                    ->label(trans('filament/resources/payment.infolist.note.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.note.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.note.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.note.hint'))
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->label(trans('filament/resources/payment.infolist.creator.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.creator.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.creator.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.creator.hint')),
                TextEntry::make('created_at')
                    ->label(trans('filament/resources/payment.infolist.created_at.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.created_at.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.created_at.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.created_at.hint'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(trans('filament/resources/payment.infolist.updated_at.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.updated_at.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.updated_at.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.updated_at.hint'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(trans('filament/resources/payment.infolist.deleted_at.label'))
                    ->placeholder(trans('filament/resources/payment.infolist.deleted_at.placeholder'))
                    ->helperText(trans('filament/resources/payment.infolist.deleted_at.helper_text'))
                    ->hint(trans('filament/resources/payment.infolist.deleted_at.hint'))
                    ->dateTime()
                    ->visible(fn (Payment $record): bool => $record->trashed()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference')
            ->modelLabel(trans('filament/resources/payment.label'))
            ->columns([
                TextColumn::make('reference')
                    ->label(trans('filament/resources/payment.table.columns.reference'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('method')
                    ->label(trans('filament/resources/payment.table.columns.method'))
                    ->badge()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('amount')
                    ->label(trans('filament/resources/payment.table.columns.amount'))
                    ->money()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('note')
                    ->label(trans('filament/resources/payment.table.columns.reference'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creator.name')
                    ->label(trans('filament/resources/payment.table.columns.reference'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(trans('filament/resources/payment.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(trans('filament/resources/payment.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label(trans('filament/resources/payment.table.columns.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn (): bool => ! $this->isOwnerOrderPaid())
                    ->fillForm(fn (): array => [
                        'amount' => $this->getPendingBalanceAmount(),
                    ])
                    ->using(function (array $data): Model {
                        if ($this->isOwnerOrderPaid()) {
                            throw ValidationException::withMessages([
                                'amount' => trans('filament/resources/payment.form.errors.order_paid'),
                            ]);
                        }

                        if (blank(data_get($data, 'amount'))) {
                            $data['amount'] = $this->getPendingBalanceAmount();
                        }

                        $data['order_id'] = $this->getOwnerRecord()->id;

                        return CreatePayment::execute($data);
                    })
                    ->modalWidth('lg'),
            ])
            ->recordActions([
                ViewAction::make()->modalWidth('lg'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }

    protected function getPendingBalanceAmount(): float
    {
        $order = $this->getOwnerRecord();
        $paidAmount = (float) $order->payments()->sum('amount');
        $orderTotal = (float) ($order->total ?? 0);

        return max(0, round($orderTotal - $paidAmount, 2));
    }

    protected function isOwnerOrderPaid(): bool
    {
        $orderTotal = (float) ($this->getOwnerRecord()->total ?? 0);

        if ($orderTotal <= 0) {
            return false;
        }

        return $this->getPendingBalanceAmount() <= 0;
    }
}
