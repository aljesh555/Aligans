<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $navigationGroup = 'Communications';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'subject';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count() ?: null;
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('status', 'new')->count() > 0
            ? 'warning'
            : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Message')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->disabled()
                            ->rows(5),
                    ]),
                    
                Forms\Components\Section::make('Admin Section')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'read' => 'Read',
                                'replied' => 'Replied',
                                'spam' => 'Spam',
                            ])
                            ->required(),
                            
                        Forms\Components\Textarea::make('notes')
                            ->placeholder('Add private notes about this message')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'new',
                        'warning' => 'read',
                        'success' => 'replied',
                        'gray' => 'spam',
                    ]),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'replied' => 'Replied',
                        'spam' => 'Spam',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->after(function (ContactMessage $record) {
                        if ($record->status === 'new') {
                            $record->update(['status' => 'read']);
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_as_replied')
                    ->label('Mark as Replied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ContactMessage $record) => $record->status != 'replied')
                    ->action(function (ContactMessage $record) {
                        $record->update(['status' => 'replied']);
                        
                        Notification::make()
                            ->title('Message marked as replied')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('mark_as_spam')
                    ->label('Mark as Spam')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn (ContactMessage $record) => $record->status != 'spam')
                    ->requiresConfirmation()
                    ->action(function (ContactMessage $record) {
                        $record->update(['status' => 'spam']);
                        
                        Notification::make()
                            ->title('Message marked as spam')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_read')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-eye')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                if ($record->status === 'new') {
                                    $record->update(['status' => 'read']);
                                }
                            });
                            
                            Notification::make()
                                ->title('Messages marked as read')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('mark_as_replied')
                        ->label('Mark as Replied')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'replied']);
                            });
                            
                            Notification::make()
                                ->title('Messages marked as replied')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('mark_as_spam')
                        ->label('Mark as Spam')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'spam']);
                            });
                            
                            Notification::make()
                                ->title('Messages marked as spam')
                                ->success()
                                ->send();
                        }),
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
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
} 