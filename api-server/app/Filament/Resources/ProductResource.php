<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\Concerns\FixesImageUploads;

class ProductResource extends Resource
{
    use FixesImageUploads;

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                                                $set('slug', Str::slug($state)))
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(1),
                                    ]),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('category_id')
                                            ->label('Category')
                                            ->optionsLimit(100)
                                            ->searchable()
                                            ->required()
                                            ->preload()
                                            ->allowHtml()
                                            ->options(function () {
                                                // Get top-level categories
                                                $topCategories = Category::whereNull('parent_id')
                                                    ->orderBy('name')
                                                    ->get();
                                                
                                                $options = [];
                                                
                                                // Format options with hierarchical structure
                                                foreach ($topCategories as $topCategory) {
                                                    // Add top category with bold formatting
                                                    $options[$topCategory->id] = "<strong>{$topCategory->name}</strong>";
                                                    
                                                    // Get subcategories for this top category
                                                    $subcategories = Category::where('parent_id', $topCategory->id)
                                                        ->orderBy('name')
                                                        ->get();
                                                    
                                                    // Add subcategories with indentation
                                                    foreach ($subcategories as $subcategory) {
                                                        $options[$subcategory->id] = "&nbsp;&nbsp;└─ {$subcategory->name}";
                                                    }
                                                }
                                                
                                                return $options;
                                            })
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255),
                                                Forms\Components\Textarea::make('description')
                                                    ->maxLength(1000),
                                            ])
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('brand_id')
                                            ->label('Brand')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                                                        $set('slug', Str::slug($state))),
                                                    
                                                Forms\Components\TextInput::make('slug')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('brands', 'slug'),
                                                    
                                                Forms\Components\FileUpload::make('logo_url')
                                                    ->label('Brand Logo')
                                                    ->image()
                                                    ->directory('brands'),
                                            ])
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU (Stock Keeping Unit)')
                                            ->helperText('A unique identifier for this product')
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->columnSpan(1),
                                    ]),

                                Forms\Components\Grid::make(1)
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                                'out_of_stock' => 'Out of Stock',
                                                'discontinued' => 'Discontinued',
                                            ])
                                            ->default('active')
                                            ->required()
                                            ->columnSpan(1),
                                    ]),

                                Forms\Components\Textarea::make('short_description')
                                    ->label('Short Description')
                                    ->helperText('A brief summary displayed in product listings (max 500 characters)')
                                    ->maxLength(500)
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Full Description')
                                    ->helperText('Detailed product information displayed on the product page')
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

                        Forms\Components\Tabs\Tab::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\Section::make('Pricing Information')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->prefix('Rs')
                                                    ->required()
                                                    ->step(0.01)
                                                    ->minValue(0)
                                                    ->columnSpan(1),

                                                Forms\Components\Toggle::make('on_sale')
                                                    ->label('On Sale')
                                                    ->helperText('Toggle to enable sale pricing')
                                                    ->default(false)
                                                    ->reactive()
                                                    ->columnSpan(1),

                                                Forms\Components\Placeholder::make('price_notice')
                                                    ->content('Configure sale details in the fields below when "On Sale" is enabled')
                                                    ->visible(fn (Forms\Get $get) => !$get('on_sale'))
                                                    ->columnSpan(1),
                                            ]),

                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\TextInput::make('discount_price')
                                                    ->label('Sale Price')
                                                    ->numeric()
                                                    ->prefix('Rs')
                                                    ->step(0.01)
                                                    ->minValue(0)
                                                    ->visible(fn (Forms\Get $get) => $get('on_sale'))
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Forms\Set $set, ?string $state, Forms\Get $get) {
                                                        $regularPrice = $get('price');
                                                        if ($regularPrice > 0 && $state > 0) {
                                                            $discountPercent = round((($regularPrice - $state) / $regularPrice) * 100);
                                                            $set('discount_percent', $discountPercent);
                                                        }
                                                    })
                                                    ->columnSpan(1),

                                                Forms\Components\TextInput::make('discount_percent')
                                                    ->label('Discount Percentage')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->minValue(0)
                                                    ->maxValue(100)
                                                    ->visible(fn (Forms\Get $get) => $get('on_sale'))
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Forms\Set $set, ?string $state, Forms\Get $get) {
                                                        $regularPrice = $get('price');
                                                        if ($regularPrice > 0 && $state > 0) {
                                                            $discountPrice = round($regularPrice * (1 - $state / 100), 2);
                                                            $set('discount_price', $discountPrice);
                                                        }
                                                    })
                                                    ->columnSpan(1),

                                                Forms\Components\DateTimePicker::make('sale_ends_at')
                                                    ->label('Sale Ends At')
                                                    ->helperText('Leave empty for no end date')
                                                    ->visible(fn (Forms\Get $get) => $get('on_sale'))
                                                    ->columnSpan(1),
                                            ]),
                                    ]),

                                Forms\Components\Section::make('Inventory Management')
                                    ->schema([
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stock Quantity')
                                            ->helperText('Number of items available for purchase')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make('Product Flags')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\Toggle::make('featured')
                                                    ->label('Featured Product')
                                                    ->helperText('Featured products will be displayed on the homepage')
                                                    ->default(false),

                                                Forms\Components\Toggle::make('is_new_arrival')
                                                    ->label('New Arrival')
                                                    ->helperText('New arrivals will be displayed in the new arrivals section')
                                                    ->default(false),
                                            ]),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Media')
                            ->schema([
                                Forms\Components\Section::make('Product Images')
                                    ->description('Upload high-quality images to showcase your product. Recommended size: 1200x1200px')
                                    ->schema([
                                        static::fixImageUpload(
                                            Forms\Components\FileUpload::make('image_url')
                                                ->label('Main Product Image')
                                                ->helperText('Primary product image shown on product pages and listings'),
                                            'products'
                                        )
                                            ->columnSpanFull(),
                                    ]),

                                    Forms\Components\Section::make('Additional Product Images')
                                        ->description('Add multiple images to show different angles or features of your product')
                                        ->schema([
                                            Forms\Components\Repeater::make('productImages')
                                                ->relationship()
                                                ->schema([
                                                    static::fixImageUpload(
                                                        Forms\Components\FileUpload::make('image_url')
                                                            ->label('Image')
                                                            ->required(),
                                                        'product-additional'
                                                    ),
                                                    
                                                    Forms\Components\TextInput::make('sort_order')
                                                        ->label('Display Order')
                                                        ->numeric()
                                                        ->default(0)
                                                        ->helperText('Lower numbers appear first'),
                                                    
                                                    Forms\Components\Toggle::make('is_active')
                                                        ->label('Active')
                                                        ->default(true),
                                                ])
                                                ->itemLabel(fn (array $state): ?string => 
                                                    isset($state['image_url']) ? "Image {$state['sort_order']}" : null)
                                                ->addActionLabel('Add Image')
                                                ->reorderable()
                                                ->defaultItems(0)
                                                ->columns(1),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Variants')
                            ->schema([
                                Forms\Components\Section::make('Product Variants')
                                    ->description('Add size, color, dimensions, and other variant attributes')
                                    ->schema([
                                        Forms\Components\Repeater::make('variants')
                                            ->schema([
                                                Forms\Components\TextInput::make('attribute')
                                                    ->label('Attribute Name')
                                                    ->required()
                                                    ->placeholder('Size, Color, Dimensions, etc.'),
                                                Forms\Components\TextInput::make('value')
                                                    ->label('Value')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->itemLabel(fn (array $state): ?string =>
                                                $state['attribute'] ? "{$state['attribute']}: {$state['value']}" : null)
                                            ->addActionLabel('Add Variant Attribute')
                                            ->reorderable()
                                            ->collapsible()
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO & Metadata')
                            ->schema([
                                Forms\Components\Section::make('Search Engine Optimization')
                                    ->description('Improve your product visibility in search results')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->helperText('Custom title tag for this product (defaults to product name if empty)')
                                            ->maxLength(70),

                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->helperText('Brief description appearing in search results (max 160 characters)')
                                            ->maxLength(160)
                                            ->rows(2),

                                        Forms\Components\TagsInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Keywords relevant to this product, separated by commas')
                                            ->separator(','),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\IconColumn::make('has_variants')
                    ->label('Has Variants')
                    ->boolean()
                    ->getStateUsing(fn (Product $record): bool =>
                        !empty($record->variants))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-minus-small')
                    ->size('md'),

                Tables\Columns\TextColumn::make('price')
                    ->prefix('Rs. ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\IconColumn::make('on_sale')
                    ->label('On Sale')
                    ->boolean()
                    ->trueIcon('heroicon-o-tag')
                    ->falseIcon('heroicon-o-x-mark'),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_new_arrival')
                    ->label('New Arrival')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'out_of_stock' => 'warning',
                        'discontinued' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'out_of_stock' => 'Out of Stock',
                        'discontinued' => 'Discontinued',
                    ]),

                Tables\Filters\TernaryFilter::make('on_sale')
                    ->label('On Sale'),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('is_new_arrival')
                    ->label('New Arrival'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleFeatured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn (Collection $records) => $records->each(fn (Product $record) => $record->update(['featured' => !$record->featured]))),
                    Tables\Actions\BulkAction::make('toggleNewArrival')
                        ->label('Toggle New Arrival')
                        ->icon('heroicon-o-sparkles')
                        ->action(fn (Collection $records) => $records->each(fn (Product $record) => $record->update(['is_new_arrival' => !$record->is_new_arrival]))),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
