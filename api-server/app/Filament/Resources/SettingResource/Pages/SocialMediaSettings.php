<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SocialMediaSettings extends Page
{
    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.social-media-settings';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $socialLinks = Setting::get('social_links', [
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'youtube' => ''
        ]);
        
        $this->form->fill($socialLinks);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Social Media Links')
                    ->description('Configure links to your social media profiles')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->url()
                            ->placeholder('https://facebook.com/your-page')
                            ->prefixIcon('heroicon-o-globe-alt'),
                            
                        Forms\Components\TextInput::make('instagram')
                            ->label('Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/your-username')
                            ->prefixIcon('heroicon-o-globe-alt'),
                            
                        Forms\Components\TextInput::make('twitter')
                            ->label('Twitter')
                            ->url()
                            ->placeholder('https://twitter.com/your-username')
                            ->prefixIcon('heroicon-o-globe-alt'),
                            
                        Forms\Components\TextInput::make('youtube')
                            ->label('YouTube')
                            ->url()
                            ->placeholder('https://youtube.com/channel/your-channel')
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ]),
                
                Forms\Components\Section::make('Add New Social Media')
                    ->description('Add a new social media platform')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('new_platform')
                            ->label('Platform Name')
                            ->placeholder('e.g. LinkedIn, TikTok')
                            ->helperText('The name of the social media platform'),
                            
                        Forms\Components\TextInput::make('new_url')
                            ->label('URL')
                            ->url()
                            ->placeholder('https://example.com/your-profile')
                            ->helperText('The full URL to your profile on this platform'),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function save(): void
    {
        // Get the current social links
        $socialLinks = Setting::get('social_links', []);
        
        // Update existing social links
        foreach (['facebook', 'instagram', 'twitter', 'youtube'] as $platform) {
            if (isset($this->data[$platform])) {
                $socialLinks[$platform] = $this->data[$platform];
            }
        }
        
        // Add new social media if provided
        if (!empty($this->data['new_platform']) && !empty($this->data['new_url'])) {
            $platform = strtolower($this->data['new_platform']);
            $socialLinks[$platform] = $this->data['new_url'];
            
            // Clear the new platform fields
            $this->data['new_platform'] = '';
            $this->data['new_url'] = '';
        }
        
        // Save the updated social links
        Setting::set('social_links', $socialLinks, 'json', 'social', 'Social media links');
        
        // Show success notification
        Notification::make()
            ->title('Social media links saved successfully')
            ->success()
            ->send();
            
        // Refresh the form with the updated data
        $this->form->fill($socialLinks);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->color('primary'),
        ];
    }
} 