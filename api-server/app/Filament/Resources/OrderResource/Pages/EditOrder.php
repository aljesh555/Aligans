<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('markAsProcessing')
                ->label('Mark as Processing')
                ->action(function () {
                    $this->record->status = 'processing';
                    $this->record->save();
                    $this->notify('success', 'Order status updated to Processing');
                })
                ->visible(fn ($record) => $record->status === 'pending')
                ->color('info')
                ->icon('heroicon-o-check-circle'),
            Actions\Action::make('markAsShipped')
                ->label('Mark as Shipped')
                ->action(function () {
                    $this->record->status = 'shipped';
                    $this->record->save();
                    $this->notify('success', 'Order status updated to Shipped');
                })
                ->visible(fn ($record) => $record->status === 'processing')
                ->color('info')
                ->icon('heroicon-o-truck'),
            Actions\Action::make('markAsDelivered')
                ->label('Mark as Delivered')
                ->action(function () {
                    $this->record->status = 'delivered';
                    $this->record->save();
                    $this->notify('success', 'Order status updated to Delivered');
                })
                ->visible(fn ($record) => $record->status === 'shipped')
                ->color('success')
                ->icon('heroicon-o-check-badge'),
            Actions\Action::make('markAsCancelled')
                ->label('Cancel Order')
                ->action(function () {
                    $this->record->status = 'cancelled';
                    $this->record->save();
                    $this->notify('success', 'Order has been cancelled');
                })
                ->visible(fn ($record) => in_array($record->status, ['pending', 'processing']))
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation(),
            Actions\Action::make('generateInvoice')
                ->label('Generate Invoice')
                ->url(fn ($record) => route('api.orders.invoice', ['id' => $record->id]))
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->openUrlInNewTab(),
        ];
    }
}
