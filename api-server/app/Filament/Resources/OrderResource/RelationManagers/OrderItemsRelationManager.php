<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('size')
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\KeyValue::make('variant_details')
                    ->keyLabel('Attribute')
                    ->valueLabel('Value')
                    ->columnSpan(2),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('discounted_price')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('variant_details')
                    ->label('Variants')
                    ->formatStateUsing(fn ($state) => $state ? collect($state)->map(fn ($value, $key) => "$key: $value")->join(', ') : '')
                    ->tooltip(fn ($record) => $record->variant_details ? collect($record->variant_details)->map(fn ($value, $key) => "$key: $value")->join(', ') : '')
                    ->toggleable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discounted_price')
                    ->money()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->money()
                    ->getStateUsing(function ($record) {
                        $price = $record->discounted_price ?: $record->price;
                        return $price * $record->quantity;
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
} 