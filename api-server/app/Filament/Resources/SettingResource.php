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
                        'repeater' => 'Repeater',
                        'image' => 'Image',
                        'file' => 'File',
                    ])
                    // ->default('repeater')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state === 'repeater') {
                            $set('value', []);
                        } else {
                            $set('value', '');
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
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('fileValue')
                            ->visible(function ($get) {
                                return $get('type') === 'image' || $get('type') === 'file';
                            })
                            ->required(function ($get) {
                                return $get('type') === 'image' || $get('type') === 'file';
                            }),
                        Forms\Components\TextInput::make('stringValue')
                            ->required()
                            ->visible(function ($get) {
                                return $get('type') === 'string';
                            })
                            ->required(function ($get) {
                                return $get('type') === 'string';
                            })
                            ->maxLength(255),
                        Forms\Components\Repeater::make('repeaterValue')
                            ->schema([
                                Forms\Components\TextInput::make('key')->required(),
                                Forms\Components\TextInput::make('value')->required()
                            ])
                            ->visible(function ($get) {
                                return $get('type') === 'repeater';
                            })
                            ->required(function ($get) {
                                return $get('type') === 'repeater';
                            }),
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
        ];
    }
}
