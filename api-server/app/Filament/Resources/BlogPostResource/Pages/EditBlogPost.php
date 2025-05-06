<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Filament\Resources\Concerns\FixesImageUploads;
use Illuminate\Support\Facades\Storage;

class EditBlogPost extends EditRecord
{
    use FixesImageUploads;
    
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            
            // Add image replacement actions
            static::createReplaceImageAction('featured_image', 'blog', 'Replace Featured Image'),
            static::createClearImageAction('featured_image', 'Clear Featured Image'),
        ];
    }
    
    protected function afterSave(): void
    {
        Notification::make()
            ->title('Blog post updated')
            ->success()
            ->send();
    }
} 