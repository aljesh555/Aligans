<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentDetailResource\Pages;
use App\Filament\Resources\PaymentDetailResource\RelationManagers;
use App\Models\PaymentDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentDetailResource extends Resource
{
    protected static ?string $model = PaymentDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Shop Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_gateway')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('transaction_id')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_gateway')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentDetails::route('/'),
            'create' => Pages\CreatePaymentDetail::route('/create'),
            'edit' => Pages\EditPaymentDetail::route('/{record}/edit'),
        ];
    }
}
