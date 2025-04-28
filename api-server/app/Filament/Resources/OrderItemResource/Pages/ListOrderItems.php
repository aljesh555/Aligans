<?php

namespace App\Filament\Resources\OrderItemResource\Pages;

use App\Filament\Resources\OrderItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\OrderItem;

class ListOrderItems extends ListRecords
{
    protected static string $resource = OrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('size')
                ->options(function () {
                    return OrderItem::whereNotNull('size')
                        ->distinct()
                        ->pluck('size', 'size')
                        ->toArray();
                }),
            SelectFilter::make('color')
                ->options(function () {
                    return OrderItem::whereNotNull('color')
                        ->distinct()
                        ->pluck('color', 'color')
                        ->toArray();
                }),
            Filter::make('has_variants')
                ->label('Has Variant Details')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('variant_details')),
        ];
    }
}
