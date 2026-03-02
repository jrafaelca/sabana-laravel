<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->label(trans('filament/resources/product.infolist.status.label'))
                    ->placeholder(trans('filament/resources/product.infolist.status.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.status.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.status.hint'))
                    ->badge(),
                TextEntry::make('name')
                    ->label(trans('filament/resources/product.infolist.name.label'))
                    ->placeholder(trans('filament/resources/product.infolist.name.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.name.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.name.hint')),
                TextEntry::make('slug')
                    ->label(trans('filament/resources/product.infolist.slug.label'))
                    ->placeholder(trans('filament/resources/product.infolist.slug.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.slug.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.slug.hint')),
                TextEntry::make('description')
                    ->label(trans('filament/resources/product.infolist.description.label'))
                    ->placeholder(trans('filament/resources/product.infolist.description.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.description.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.description.hint')),
                TextEntry::make('cost')
                    ->label(trans('filament/resources/product.infolist.cost.label'))
                    ->placeholder(trans('filament/resources/product.infolist.cost.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.cost.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.cost.hint'))
                    ->money(),
                TextEntry::make('price')
                    ->label(trans('filament/resources/product.infolist.price.label'))
                    ->placeholder(trans('filament/resources/product.infolist.price.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.price.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.price.hint'))
                    ->money(),
                TextEntry::make('created_at')
                    ->label(trans('filament/resources/product.infolist.created_at.label'))
                    ->placeholder(trans('filament/resources/product.infolist.created_at.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.created_at.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.created_at.hint'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(trans('filament/resources/product.infolist.updated_at.label'))
                    ->placeholder(trans('filament/resources/product.infolist.updated_at.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.updated_at.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.updated_at.hint'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(trans('filament/resources/product.infolist.deleted_at.label'))
                    ->placeholder(trans('filament/resources/product.infolist.deleted_at.placeholder'))
                    ->helperText(trans('filament/resources/product.infolist.deleted_at.helper_text'))
                    ->hint(trans('filament/resources/product.infolist.deleted_at.hint'))
                    ->dateTime()
                    ->visible(fn (Product $record): bool => $record->trashed()),
            ]);
    }
}
