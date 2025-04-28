<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, array, json, etc.
            $table->string('group')->default('general'); // general, appearance, contact, etc.
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed initial settings
        $this->seedSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }

    /**
     * Seed initial settings
     *
     * @return void
     */
    private function seedSettings()
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => json_encode('Aligans'),
                'type' => 'string',
                'group' => 'general',
                'description' => 'Website name'
            ],
            [
                'key' => 'site_logo',
                'value' => json_encode('/images/logo.png'),
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'Website logo'
            ],
            [
                'key' => 'site_favicon',
                'value' => json_encode('/images/favicon.ico'),
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'Website favicon'
            ],
            [
                'key' => 'footer_copyright',
                'value' => json_encode('© ' . date('Y') . ' Aligans. All rights reserved.'),
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'Footer copyright text'
            ],
            [
                'key' => 'contact_email',
                'value' => json_encode('contact@example.com'),
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Contact email address'
            ],
            [
                'key' => 'contact_phone',
                'value' => json_encode('+1234567890'),
                'type' => 'string',
                'group' => 'contact',
                'description' => 'Contact phone number'
            ],
            [
                'key' => 'social_links',
                'value' => json_encode([
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'instagram' => 'https://instagram.com'
                ]),
                'type' => 'json',
                'group' => 'social',
                'description' => 'Social media links'
            ]
        ];

        DB::table('settings')->insert($settings);
    }
}