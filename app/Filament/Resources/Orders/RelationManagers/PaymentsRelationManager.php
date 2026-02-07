<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Actions\CreatePaymentAction;
use App\Enums\PaymentMethods;
use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

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
                    ->visible(fn(Payment $record): bool => $record->trashed()),
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
                    ->using(function (array $data ): Model {
                        $data['order_id'] = $this->getOwnerRecord()->id;
                        return CreatePaymentAction::handle($data);
                    })
                    ->modalWidth('lg'),
            ])
            ->recordActions([
                ViewAction::make()->modalWidth('lg'),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
