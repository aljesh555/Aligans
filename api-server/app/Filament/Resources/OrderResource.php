<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Summary')
                    ->description('Basic information about this order')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->label('Customer')
                                        ->required()
                                        ->searchable(),
                                        
                                    Forms\Components\Select::make('shipping_form_id')
                                        ->relationship('shippingForm', 'id')
                                        ->label('Shipping Address')
                                        ->searchable(),
                                ]),
                                
                                Forms\Components\Group::make([
                                    Forms\Components\Select::make('payment_method')
                                        ->options([
                                            'cod' => 'Cash on Delivery',
                                            'esewa' => 'eSewa',
                                            'khalti' => 'Khalti',
                                            'phonepay' => 'PhonePay',
                                            'credit_card' => 'Credit Card',
                                            'paypal' => 'PayPal',
                                        ])
                                        ->required(),
                                        
                                    Forms\Components\TextInput::make('total_amount')
                                        ->label('Total Amount')
                                        ->required()
                                        ->numeric()
                                        ->prefix('Rs.'),
                                ]),
                            ]),
                    ]),
                    
                Forms\Components\Section::make('Order Status')
                    ->description('Current status of this order')
                    ->schema([
                        // Order Progress Visualization
                        Forms\Components\Placeholder::make('order_progress')
                            ->label('Order Progress')
                            ->content(function ($record) {
                                if (!$record) return 'Available after order creation';
                                
                                $steps = [
                                    'pending' => ['label' => 'Pending', 'icon' => '📦', 'description' => 'Order received'],
                                    'processing' => ['label' => 'Processing', 'icon' => '🔧', 'description' => 'Preparing order'],
                                    'shipped' => ['label' => 'Shipped', 'icon' => '🚚', 'description' => 'Order in transit'],
                                    'delivered' => ['label' => 'Delivered', 'icon' => '✅', 'description' => 'Order complete'],
                                ];
                                
                                $currentStep = $record->status;
                                
                                // Don't show the workflow if order is cancelled
                                if ($currentStep === 'cancelled') {
                                    return '<div class="flex items-center p-2 bg-red-100 text-red-700 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg> This order has been cancelled</div>';
                                }
                                
                                $stepOrder = array_keys($steps);
                                $currentStepIndex = array_search($currentStep, $stepOrder);
                                
                                $html = '<div class="py-2">';
                                $html .= '<div class="flex items-center justify-between relative">';
                                
                                // Progress bar
                                $html .= '<div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -translate-y-1/2 z-0"></div>';
                                
                                // Calculate progress percentage
                                $progressPercent = 0;
                                if ($currentStepIndex !== false) {
                                    $progressPercent = ($currentStepIndex / (count($stepOrder) - 1)) * 100;
                                }
                                
                                // Filled progress bar
                                $html .= '<div class="absolute top-1/2 left-0 h-1 bg-primary-500 -translate-y-1/2 z-0" style="width: ' . $progressPercent . '%"></div>';
                                
                                // Steps
                                foreach ($stepOrder as $index => $step) {
                                    $isPast = $index < $currentStepIndex;
                                    $isCurrent = $index === $currentStepIndex;
                                    $isFuture = $index > $currentStepIndex;
                                    
                                    $stepClasses = 'flex flex-col items-center relative z-10';
                                    $circleClasses = 'flex items-center justify-center w-10 h-10 rounded-full text-lg ';
                                    
                                    if ($isPast) {
                                        $circleClasses .= 'bg-primary-100 text-primary-700 border-2 border-primary-500';
                                    } elseif ($isCurrent) {
                                        $circleClasses .= 'bg-primary-500 text-white';
                                    } else {
                                        $circleClasses .= 'bg-gray-100 text-gray-400 border border-gray-300';
                                    }
                                    
                                    $html .= '<div class="' . $stepClasses . '">';
                                    $html .= '<div class="' . $circleClasses . '">' . $steps[$step]['icon'] . '</div>';
                                    $html .= '<div class="text-xs mt-2 font-medium ' . ($isCurrent ? 'text-primary-700' : ($isPast ? 'text-gray-700' : 'text-gray-400')) . '">' . $steps[$step]['label'] . '</div>';
                                    $html .= '</div>';
                                }
                                
                                $html .= '</div>';
                                
                                // Current status description
                                $html .= '<div class="mt-4 p-2 bg-gray-50 rounded text-sm">';
                                $html .= '<span class="font-medium">Current Status:</span> ' . $steps[$currentStep]['label'] . ' - ' . $steps[$currentStep]['description'];
                                
                                if ($currentStep === 'pending') {
                                    $html .= '<div class="mt-1 text-xs text-gray-500">Click "Start Processing" when you begin preparing this order</div>';
                                } elseif ($currentStep === 'processing') {
                                    $html .= '<div class="mt-1 text-xs text-gray-500">Click "Mark as Shipped" when the order is sent out for delivery</div>';
                                } elseif ($currentStep === 'shipped') {
                                    $html .= '<div class="mt-1 text-xs text-gray-500">Click "Confirm Delivery" when the customer receives the order</div>';
                                }
                                
                                $html .= '</div>';
                                
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Group::make([
                                    Forms\Components\Select::make('status')
                                        ->label('Fulfillment Status')
                                        ->options([
                                            'pending' => 'Pending - New order',
                                            'processing' => 'Processing - Preparing order',
                                            'shipped' => 'Shipped - Order in transit',
                                            'delivered' => 'Delivered - Order received',
                                            'cancelled' => 'Cancelled - Order stopped',
                                        ])
                                        ->required()
                                        ->default('pending')
                                        ->disabled(fn ($record) => $record && $record->status === 'cancelled')
                                        ->helperText('Use the buttons above to change status more easily')
                                        ->extraAttributes(['class' => 'focus:border-primary-500']),
                                ]),
                                
                                // Remove Payment Status Group But Keep It Hidden For Functionality
                                Forms\Components\Hidden::make('payment_status')
                                    ->default('unpaid')
                                    ->dehydrated(),
                            ]),
                    ]),

                // Remove Payment Information Section But Keep It Hidden For Functionality
                Forms\Components\Hidden::make('payment_detail_id'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Order Status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        'unpaid' => 'warning',
                        'paid' => 'success',
                        'refunded' => 'info',
                        'failed' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state ?: 'Unknown'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('NPR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
