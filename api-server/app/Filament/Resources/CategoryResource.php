<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Shop Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(Category::where('parent_id', null)->pluck('name', 'id'))
                    ->placeholder('Select parent category (optional)')
                    ->helperText('Leave empty for top-level category')
                    ->nullable(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\FileUpload::make('image_url')
                    ->image(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive'
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->parent_id === null ? 'Top Category' : 'Subcategory')
                    ->color(fn ($record) => $record->parent_id === null ? 'primary' : 'secondary'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        if ($record->parent_id === null) {
                            return '<strong>' . $record->name . '</strong>';
                        }
                        return '└─ ' . $record->name;
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // First prioritize parent_id (null values first), then sort by name
                return $query->orderBy('parent_id', 'asc')
                            ->orderBy('name', 'asc');
            })
            ->groups([
                Tables\Grouping\Group::make('parent.name')
                    ->label('Group by Parent Category')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn ($record) => $record->parent ? $record->parent->name : 'Top Categories')
                    ->collapsible(),
            ])
            ->defaultGroup('parent.name')
            ->filters([
                Tables\Filters\SelectFilter::make('category_type')
                    ->label('Category Type')
                    ->options([
                        'top' => 'Top Categories',
                        'sub' => 'Subcategories',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'top') {
                            $query->whereNull('parent_id');
                        } elseif ($data['value'] === 'sub') {
                            $query->whereNotNull('parent_id');
                        }
                    })
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
