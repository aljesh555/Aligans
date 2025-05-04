<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            
            // Next Steps Group - Shows what the admin can do next based on current status
            Actions\ActionGroup::make([
                Actions\Action::make('markAsProcessing')
                    ->label('Start Processing')
                    ->tooltip('Move order to processing stage')
                    ->color('info')
                    ->icon('heroicon-o-clock')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function () {
                        $this->record->status = 'processing';
                        $this->record->save();
                        Notification::make()
                            ->success()
                            ->title('Order status updated to Processing')
                            ->body('The team can now start preparing this order')
                            ->send();
                    }),
                
                Actions\Action::make('markAsShipped')
                    ->label('Mark as Shipped')
                    ->tooltip('Order has been sent for delivery')
                    ->color('info')
                    ->icon('heroicon-o-truck')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->action(function () {
                        $this->record->status = 'shipped';
                        $this->record->save();
                        Notification::make()
                            ->success()
                            ->title('Order is now marked as Shipped')
                            ->body('The order is on its way to the customer')
                            ->send();
                    }),
                
                Actions\Action::make('markAsDelivered')
                    ->label('Confirm Delivery')
                    ->tooltip('Order has been delivered to customer')
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->visible(fn ($record) => $record->status === 'shipped')
                    ->action(function () {
                        DB::beginTransaction();
                        try {
                            // Update order status
                            $this->record->status = 'delivered';
                            $this->record->save();
                            
                            // For COD orders, automatically update payment status to paid
                            if ($this->record->payment_method === 'cod') {
                                $paymentDetail = PaymentDetail::where('order_id', $this->record->id)->first();
                                if ($paymentDetail) {
                                    $paymentDetail->payment_status = 'completed';
                                    $paymentDetail->payment_date = now();
                                    $paymentDetail->save();
                                    
                                    // Also update order payment status if such column exists
                                    if (isset($this->record->payment_status)) {
                                        $this->record->payment_status = 'paid';
                                        $this->record->save();
                                    }
                                }
                            }
                            
                            DB::commit();
                            
                            Notification::make()
                                ->success()
                                ->title('Delivery Confirmed')
                                ->body($this->record->payment_method === 'cod' 
                                    ? 'For Cash on Delivery, payment has been automatically marked as Paid' 
                                    : 'Order status is now complete')
                                ->send();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->danger()
                                ->title('Error updating order')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])->label('Order Progress')
                ->color('success')
                ->icon('heroicon-o-arrow-path'),
            
            // Payment Management (Hidden but functional)
            Actions\Action::make('updatePaymentStatus')
                ->label('Update Payment')
                ->tooltip('Change the payment status')
                ->color('warning')
                ->icon('heroicon-o-currency-dollar')
                ->hidden()
                ->form(function() {
                    $paymentDetail = PaymentDetail::where('order_id', $this->record->id)->first();
                    $isCod = $this->record->payment_method === 'cod';
                    
                    return [
                        \Filament\Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Unpaid - Payment not received',
                                'completed' => 'Paid - Payment received',
                                'failed' => 'Failed - Payment attempt failed',
                                'refunded' => 'Refunded - Payment returned to customer',
                            ])
                            ->default($paymentDetail ? $paymentDetail->payment_status : 'pending')
                            ->reactive()
                            ->required(),
                            
                        \Filament\Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->placeholder($isCod ? 'Enter receipt or reference number' : 'System will generate if left empty')
                            ->default($paymentDetail ? $paymentDetail->transaction_id : '')
                            ->helperText($isCod 
                                ? 'For Cash on Delivery, enter a receipt number or manual reference' 
                                : 'For online payments, transaction ID from payment gateway')
                            ->required(function(callable $get) use ($isCod) {
                                // Only require transaction ID for COD payments that are being marked as completed
                                return $isCod && $get('payment_status') === 'completed';
                            })
                    ];
                })
                ->action(function (array $data): void {
                    $paymentDetail = PaymentDetail::where('order_id', $this->record->id)->first();
                    
                    if ($paymentDetail) {
                        $paymentDetail->payment_status = $data['payment_status'];
                        
                        // Update transaction ID if provided
                        if (isset($data['transaction_id']) && !empty($data['transaction_id'])) {
                            $paymentDetail->transaction_id = $data['transaction_id'];
                        }
                        // Generate a transaction ID if not provided and payment is completed (for online payments only)
                        else if ($data['payment_status'] === 'completed' && 
                                empty($paymentDetail->transaction_id) &&
                                $this->record->payment_method !== 'cod') {
                            $paymentDetail->transaction_id = 'TXN' . strtoupper($this->record->payment_method) . time() . rand(1000, 9999);
                        }
                        
                        // Set payment date for completed payments
                        if ($data['payment_status'] === 'completed') {
                            $paymentDetail->payment_date = now();
                        }
                        
                        $paymentDetail->save();
                        
                        // Also update order payment_status if such column exists
                        if (isset($this->record->payment_status)) {
                            $mappedStatus = [
                                'pending' => 'unpaid',
                                'completed' => 'paid',
                                'failed' => 'failed',
                                'refunded' => 'refunded',
                            ];
                            
                            if (isset($mappedStatus[$data['payment_status']])) {
                                $this->record->payment_status = $mappedStatus[$data['payment_status']];
                                $this->record->save();
                            }
                        }
                        
                        $messages = [
                            'pending' => 'Payment status is now Unpaid',
                            'completed' => 'Payment recorded as Paid successfully',
                            'failed' => 'Payment marked as Failed',
                            'refunded' => 'Payment has been marked as Refunded',
                        ];
                        
                        Notification::make()
                            ->success()
                            ->title('Payment Updated')
                            ->body($messages[$data['payment_status']] ?? 'Payment status updated')
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Payment record not found')
                            ->send();
                    }
                }),
            
            // Order Actions Group
            Actions\ActionGroup::make([
                Actions\Action::make('markAsCancelled')
                    ->label('Cancel Order')
                    ->tooltip('Cancel this order and restore stock')
                    ->action(function () {
                        // Update order status
                        $this->record->status = 'cancelled';
                        $this->record->save();
                        
                        // Restore product stock
                        $this->restoreProductStock($this->record);
                        
                        Notification::make()
                            ->success()
                            ->title('Order has been cancelled')
                            ->body('Products have been returned to inventory')
                            ->send();
                    })
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'processing']))
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation(),
                    
                Actions\Action::make('generateInvoice')
                    ->label('Generate Invoice')
                    ->tooltip('Create a PDF invoice')
                    ->url(fn ($record) => route('api.orders.invoice', ['orderId' => $record->id]))
                    ->color('gray')
                    ->icon('heroicon-o-document-text')
                    ->openUrlInNewTab(),
            ])->label('Other Actions')
              ->color('gray')
              ->icon('heroicon-o-ellipsis-horizontal'),
        ];
    }
    
    /**
     * Restore product stock when an order is cancelled
     * 
     * @param \App\Models\Order $order
     * @return void
     */
    protected function restoreProductStock($order)
    {
        // Get all order items
        $orderItems = $order->orderItems;
        
        foreach ($orderItems as $item) {
            // Find the product
            $product = $item->product;
            
            if ($product) {
                // Add the quantity back to stock
                $product->stock += $item->quantity;
                $product->save();
                
                // Log the stock restoration
                \Illuminate\Support\Facades\Log::info(
                    "Stock restored for product #{$product->id} ({$product->name}). " . 
                    "Added {$item->quantity} units back to inventory."
                );
            }
        }
    }
}
