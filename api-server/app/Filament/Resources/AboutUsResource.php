<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Filament\Resources\AboutUsResource\RelationManagers;
use App\Models\AboutUs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn;

class AboutUsResource extends Resource
{
    protected static ?string $model = AboutUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    
    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->default('About Us'),
                            
                        Forms\Components\FileUpload::make('banner_image')
                            ->image()
                            ->directory('about-us')
                            ->maxSize(5120) // 5MB
                            ->helperText('Recommended size: 1200x400px. Max 5MB.'),
                            
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Company Values')
                    ->schema([
                        Forms\Components\Textarea::make('mission')
                            ->nullable()
                            ->rows(3),
                            
                        Forms\Components\Textarea::make('vision')
                            ->nullable()
                            ->rows(3),
                            
                        Forms\Components\Textarea::make('values')
                            ->nullable()
                            ->rows(3),
                    ]),
                
                Forms\Components\Section::make('Team Members')
                    ->schema([
                        Forms\Components\Repeater::make('team')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('position')
                                    ->required(),
                                    
                                Forms\Components\FileUpload::make('photo')
                                    ->image()
                                    ->directory('team-members')
                                    ->maxSize(2048), // 2MB
                                    
                                Forms\Components\Textarea::make('bio')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ]),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Only one about page can be active at a time. Activating this will deactivate others.')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                    
                Tables\Columns\ImageColumn::make('banner_image')
                    ->label('Banner'),
                    
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AboutUs $record) => !$record->is_active)
                    ->action(function (AboutUs $record) {
                        // Deactivate all other records
                        AboutUs::where('id', '!=', $record->id)
                            ->where('is_active', true)
                            ->update(['is_active' => false]);
                        
                        // Activate this record
                        $record->update(['is_active' => true]);
                    }),
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
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }    
} 