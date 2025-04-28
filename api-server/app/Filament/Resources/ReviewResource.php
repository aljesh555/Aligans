<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    
    protected static ?string $navigationGroup = 'Shop Management';
    
    protected static ?int $navigationSort = 5;
    
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Information')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable(),
                            
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
                            
                        Forms\Components\Grid::make(3)
                            ->schema([
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
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Product Image')
                    ->circular(),
                    
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('Reviewer')
                    ->searchable()
                    ->description(fn (Review $record): string => $record->user_id ? 'Registered User' : 'Guest'),
                    
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
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_verified_purchase')
                    ->label('Purchase Status')
                    ->placeholder('All Reviews')
                    ->trueLabel('Verified Purchases')
                    ->falseLabel('Unverified Purchases'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['product', 'user']);
    }
}
