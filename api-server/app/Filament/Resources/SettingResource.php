<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    
    protected static ?string $navigationLabel = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'string' => 'String',
                        'integer' => 'Integer',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                        'array' => 'Array',
                    ])
                    ->default('string')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'json') {
                            $set('value', '{"":""}');
                        }
                    }),
                Forms\Components\Select::make('group')
                    ->required()
                    ->options([
                        'general' => 'General',
                        'appearance' => 'Appearance',
                        'contact' => 'Contact',
                        'social' => 'Social Media',
                        'seo' => 'SEO',
                    ])
                    ->default('general'),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Textarea::make('value')
                            ->label(function ($get) {
                                return $get('type') === 'json' 
                                    ? 'Value (JSON Format)' 
                                    : 'Value';
                            })
                            ->helperText(function ($get) {
                                return $get('type') === 'json'
                                    ? 'For social media, enter JSON like: {"facebook":"https://facebook.com/page", "instagram":"https://instagram.com/user"}'
                                    : 'Enter the setting value';
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
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
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'appearance' => 'Appearance',
                        'contact' => 'Contact',
                        'social' => 'Social Media',
                        'seo' => 'SEO',
                    ]),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
            'social-media' => Pages\SocialMediaSettings::route('/social-media'),
        ];
    }
}
