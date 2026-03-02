<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label(trans('filament/resources/product.form.fields.name.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.name.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.name.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.name.hint'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(190)
                    ->live(true)
                    ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label(trans('filament/resources/product.form.fields.slug.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.slug.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.slug.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.slug.hint'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(190)
                    ->unique(Product::class, 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->label(trans('filament/resources/product.form.fields.description.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.description.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.description.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.description.hint'))
                    ->minLength(2)
                    ->maxLength(190)
                    ->autosize(),
                TextInput::make('cost')
                    ->label(trans('filament/resources/product.form.fields.cost.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.cost.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.cost.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.cost.hint'))
                    ->numeric(),
                TextInput::make('price')
                    ->label(trans('filament/resources/product.form.fields.price.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.price.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.price.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.price.hint'))
                    ->required()
                    ->numeric()
                    ->gte('cost')
                    ->default(0),
                Select::make('status')
                    ->label(trans('filament/resources/product.form.fields.status.label'))
                    ->placeholder(trans('filament/resources/product.form.fields.status.placeholder'))
                    ->helperText(trans('filament/resources/product.form.fields.status.helper_text'))
                    ->hint(trans('filament/resources/product.form.fields.status.hint'))
                    ->hintIcon(Heroicon::OutlinedQuestionMarkCircle, tooltip: trans('filament/resources/product.form.fields.status.hint_tooltip'))
                    ->required()
                    ->options(ProductStatus::class)
                    ->searchable()
                    ->default(ProductStatus::Enabled),
            ]);
    }
}
