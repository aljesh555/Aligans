<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some initial static pages
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>About Our Company</h2><p>Welcome to Aligans, your trusted source for quality sports equipment in Nepal. We have been serving athletes and sports enthusiasts since 2020.</p><p>Our mission is to provide high-quality sports products at affordable prices.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<h2>Get in Touch</h2><p>We\'d love to hear from you! Contact us using the information below or fill out our contact form.</p><p><strong>Address:</strong> Baneshwor, Kathmandu, Nepal<br><strong>Phone:</strong> +977-1-4444444<br><strong>Email:</strong> info@aligans.com</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Shipping Policy',
                'slug' => 'shipping-policy',
                'content' => '<h2>Shipping Policy</h2><p>We offer reliable shipping throughout Nepal. Orders typically process within 1-2 business days.</p><p>Standard shipping takes 3-5 business days within Kathmandu Valley and 5-7 business days for other regions.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Return Policy',
                'slug' => 'return-policy',
                'content' => '<h2>Return Policy</h2><p>We accept returns within 7 days of delivery for unused items in original packaging.</p><p>Please contact our customer service team to initiate a return.</p>',
                'is_active' => true,
            ]
        ];

        foreach ($pages as $page) {
            StaticPage::create($page);
        }
    }
} 