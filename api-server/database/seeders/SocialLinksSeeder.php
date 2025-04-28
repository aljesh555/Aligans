<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SocialLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialLinks = [
            'facebook' => 'https://www.facebook.com/aljesh.raut',
            'instagram' => 'https://www.instagram.com/aljeshraut/',
            'twitter' => 'https://twitter.com',
            'youtube' => 'https://youtube.com'
        ];
        
        Setting::set('social_links', $socialLinks, 'json', 'social', 'Social media links');
        
        $this->command->info('Social links have been set.');
    }
}
