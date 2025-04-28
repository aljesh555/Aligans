<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogoResource\Pages;
use App\Models\Logo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\Action;

class LogoResource extends Resource
{
    protected static ?string $model = Logo::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
                    ->label('Logo Image')
                    ->image()
                    ->maxSize(2048)
                    ->directory('logos')
                    ->visibility('public')
                    ->required()
                    ->helperText('Upload a logo image (PNG or SVG recommended for transparency)'),
                Toggle::make('is_active')
                    ->label('Set as Active Logo')
                    ->helperText('Only one logo can be active at a time. Activating this will deactivate all others.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Logo')
                    ->circular(false)
                    ->square()
                    ->extraImgAttributes(['class' => 'object-contain h-16 w-auto'])
                    ->url(fn (Logo $record): string => url($record->image_path))
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            // Deactivate all other logos
                            Logo::where('id', '<>', $record->id)
                                ->update(['is_active' => false]);
                        }
                    }),
            ])
            ->actions([
                Action::make('activate')
                    ->label('Set as Active')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Logo $record): bool => !$record->is_active)
                    ->action(function (Logo $record) {
                        Logo::setActive($record->id);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Logo $record) {
                        // Delete the image file when deleting the record
                        if ($record->image_path) {
                            $path = str_replace('storage/', '', $record->image_path);
                            if (Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
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
            'index' => Pages\ListLogos::route('/'),
            'create' => Pages\CreateLogo::route('/create'),
            'edit' => Pages\EditLogo::route('/{record}/edit'),
        ];
    }
} 