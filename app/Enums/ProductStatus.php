<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductStatus: string implements HasColor, HasLabel
{
    case Enabled = 'enabled';
    case Disabled = 'disabled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Enabled => __('product.status.enabled'),
            self::Disabled => __('product.status.disabled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Enabled => Color::Green,
            self::Disabled => Color::Gray,
        };
    }
}
