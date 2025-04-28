<?php

namespace App\Filament\Resources\OrderItemResource\Pages;

use App\Filament\Resources\OrderItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderItem extends EditRecord
{
    protected static string $resource = OrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('viewOrder')
                ->label('View Order')
                ->url(fn ($record) => route('filament.admin.resources.orders.edit', ['record' => $record->order_id]))
                ->color('success')
                ->icon('heroicon-o-eye'),
        ];
    }
}
