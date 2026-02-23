<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\OrderMetricsService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;

class TopProductsTableWidget extends TableWidget
{
    use InteractsWithPageFilters;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $service = app(OrderMetricsService::class);
        $period = data_get($this->pageFilters, 'period');

        return $table
            ->heading('Top productos')
            ->query(fn () => $service->getTopProductsQuery($period))
            ->paginated([5, 10, 20])
            ->defaultPaginationPageOption(5)
            ->defaultSort('sales_total', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable(),
                TextColumn::make('units_sold')
                    ->label('Unidades')
                    ->numeric(0)
                    ->sortable(),
                TextColumn::make('sales_total')
                    ->label('Ventas')
                    ->money()
                    ->sortable(),
                TextColumn::make('gross_profit')
                    ->label('Utilidad')
                    ->money()
                    ->sortable(),
                TextColumn::make('margin_percentage')
                    ->label('Margen %')
                    ->state(fn (object $record): float => (float) $record->sales_total > 0
                        ? (((float) $record->gross_profit / (float) $record->sales_total) * 100)
                        : 0)
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2, '.', ',').'%')
                    ->sortable(),
                TextColumn::make('sales_delta_percentage')
                    ->label('Variación vs anterior')
                    ->state(function (object $record): ?float {
                        $previousSales = (float) $record->previous_sales_total;
                        $currentSales = (float) $record->sales_total;

                        if ($previousSales === 0.0) {
                            return null;
                        }

                        return (($currentSales - $previousSales) / $previousSales) * 100;
                    })
                    ->formatStateUsing(fn (?float $state): string => $state === null ? 'N/A' : number_format($state, 2, '.', ',').'%')
                    ->color(function (?float $state): string {
                        if ($state === null) {
                            return 'gray';
                        }

                        if ($state > 0) {
                            return 'success';
                        }

                        if ($state < 0) {
                            return 'danger';
                        }

                        return 'gray';
                    }),
            ]);
    }
}
