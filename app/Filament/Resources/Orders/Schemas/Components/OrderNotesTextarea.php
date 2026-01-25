<?php

namespace App\Filament\Resources\Orders\Schemas\Components;

use Filament\Forms\Components\Textarea;

class OrderNotesTextarea
{
    public static function make(): Textarea
    {
        return Textarea::make('notes')
            ->label(trans('filament/resources/order.form.fields.notes.label'))
            ->placeholder(trans('filament/resources/order.form.fields.notes.placeholder'))
            ->helperText(trans('filament/resources/order.form.fields.notes.helper_text'))
            ->hint(trans('filament/resources/order.form.fields.notes.hint'))
            ->minLength(2)
            ->maxLength(190);
    }
}
