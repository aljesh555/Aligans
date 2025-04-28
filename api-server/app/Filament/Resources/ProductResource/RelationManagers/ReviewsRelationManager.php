<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable(),
                    
                Forms\Components\TextInput::make('reviewer_name')
                    ->label('Reviewer Name')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn (Forms\Get $get) => !$get('user_id')),
                    
                Forms\Components\TextInput::make('reviewer_email')
                    ->label('Reviewer Email')
                    ->email()
                    ->maxLength(255)
                    ->visible(fn (Forms\Get $get) => !$get('user_id')),
                    
                Forms\Components\TextInput::make('title')
                    ->label('Review Title')
                    ->maxLength(255),
                    
                Forms\Components\RichEditor::make('comment')
                    ->label('Review Comment')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'undo',
                        'redo',
                    ])
                    ->columnSpanFull(),
                    
                Forms\Components\Radio::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ])
                    ->default(5)
                    ->required()
                    ->inline(),
                    
                Forms\Components\Toggle::make('is_verified_purchase')
                    ->label('Verified Purchase')
                    ->helperText('Indicates if the reviewer has purchased this product')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('Reviewer')
                    ->searchable()
                    ->description(fn ($record): string => $record->user_id ? 'Registered User' : 'Guest'),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_verified_purchase')
                    ->label('Verified Purchase')
                    ->boolean(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // No ternary filter needed
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