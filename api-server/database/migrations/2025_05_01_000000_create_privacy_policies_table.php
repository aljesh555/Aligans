<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert initial privacy policy
        DB::table('privacy_policies')->insert([
            'title' => 'Privacy Policy',
            'content' => '<h2 class="text-xl font-bold mt-8 mb-4">1. Introduction</h2>
            <p>Aligans ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase.</p>
            <p>We reserve the right to make changes to this Privacy Policy at any time and for any reason. We will alert you about any changes by updating the "Last Updated" date of this Privacy Policy. Any changes will be effective immediately upon posting on our website.</p>
            
            <h2 class="text-xl font-bold mt-8 mb-4">2. Information We Collect</h2>
            <h3 class="text-lg font-semibold mt-4 mb-2">Personal Data</h3>
            <p>We may collect personal information that you voluntarily provide to us when you register on our website, express interest in obtaining information about us or our products, or otherwise contact us.</p>',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');
    }
}; 