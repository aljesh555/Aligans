<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing FAQs
        Faq::truncate();

        // Product FAQs
        Faq::create([
            'question' => 'How do I choose the right cricket bat?',
            'answer' => 'Choosing the right cricket bat depends on several factors: <br><br><strong>1. Weight</strong> - Cricket bats typically weigh between 2lb 7oz and 3lb. Beginners and younger players should start with lighter bats.<br><strong>2. Size</strong> - Bats come in sizes 0-6 for juniors and Short Handle (SH), Long Blade (LB), and Long Handle (LH) for adults.<br><strong>3. Wood Grade</strong> - English willow bats are graded 1-5, with Grade 1 being the highest quality.<br><strong>4. Playing Style</strong> - Front-foot players often prefer lighter bats, while back-foot players may prefer heavier bats with a higher sweet spot.<br><br>We recommend visiting our store for a professional fitting.',
            'category' => 'products',
            'order' => 1,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'What type of cricket shoes should I get?',
            'answer' => 'Cricket shoes are designed for specific playing surfaces and roles:<br><br><strong>1. Spike Shoes</strong> - Metal spikes provide optimal grip on grass surfaces. Ideal for fast bowlers who need traction during their run-up and delivery.<br><strong>2. Half-Spike Shoes</strong> - Feature metal spikes at the front and rubber studs at the heel. Great for all-rounders.<br><strong>3. Rubber Studs</strong> - Suitable for artificial surfaces and indoor practice. These are versatile and can be used on multiple surfaces.<br><br>Consider your primary role (batsman, bowler, all-rounder) and the surfaces you\'ll be playing on most frequently when selecting cricket shoes.',
            'category' => 'products',
            'order' => 2,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'How often should I replace my cricket equipment?',
            'answer' => 'The lifespan of cricket equipment varies based on usage, quality, and care:<br><br><strong>Cricket Bat</strong> - A good quality bat with proper care can last 2-3 seasons for regular players. Signs of replacement include visible cracks, reduced performance, or significant damage.<br><strong>Pads and Gloves</strong> - Typically need replacement every 1-2 seasons depending on use. Look for worn straps, decreased padding, or damaged palms.<br><strong>Helmet</strong> - Should be replaced after any significant impact or every 2-3 years as safety standards evolve.<br><strong>Balls</strong> - Match balls are usually replaced per match, while practice balls can last several sessions depending on surface conditions.<br><br>Regular maintenance can extend the life of your equipment significantly.',
            'category' => 'products',
            'order' => 3,
            'is_active' => true
        ]);

        // Shipping FAQs
        Faq::create([
            'question' => 'What areas do you ship to?',
            'answer' => 'We currently ship throughout Nepal. We offer:<br><br><strong>Standard Delivery</strong> - 3-5 business days<br><strong>Express Delivery</strong> - 1-2 business days (available in major cities only)<br><br>International shipping is available to select countries. Please contact our customer service for more information about international shipping options and rates.',
            'category' => 'shipping',
            'order' => 1,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'How long does shipping take?',
            'answer' => 'Shipping times depend on your location and the shipping method chosen:<br><br><strong>Within Kathmandu Valley</strong>:<br>- Standard: 1-2 business days<br>- Express: Same day delivery (orders placed before 12 PM)<br><br><strong>Other Major Cities</strong>:<br>- Standard: 2-3 business days<br>- Express: 1-2 business days<br><br><strong>Remote Areas</strong>:<br>- Standard: 4-7 business days<br><br>Please note that these are estimated timeframes and actual delivery times may vary during peak seasons or adverse weather conditions.',
            'category' => 'shipping',
            'order' => 2,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Do you charge for shipping?',
            'answer' => 'Our shipping policy is as follows:<br><br>- Free standard shipping on all orders above NPR 5,000<br>- Orders below NPR 5,000 incur a shipping fee of NPR 150-300 depending on location<br>- Express shipping has additional charges starting from NPR 250<br><br>Shipping fees are calculated at checkout based on your delivery address and selected shipping method.',
            'category' => 'shipping',
            'order' => 3,
            'is_active' => true
        ]);

        // Returns FAQs
        Faq::create([
            'question' => 'What is your return policy?',
            'answer' => 'We accept returns within 15 days of purchase under the following conditions:<br><br>- The product must be unused, unworn, and in its original packaging<br>- Original receipt or proof of purchase is required<br>- Custom-made items (like personalized bats or jerseys) are not eligible for return<br>- Sale items may only be eligible for exchange, not refund<br><br>To initiate a return, please contact our customer service team or visit our store with your purchase.',
            'category' => 'returns',
            'order' => 1,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'How do I return or exchange an item?',
            'answer' => 'To return or exchange an item:<br><br><strong>1. Contact Us</strong> - Reach out to our customer service team via phone, email, or the contact form on our website within 15 days of receiving your order.<br><strong>2. Return Authorization</strong> - We\'ll provide you with a return authorization number and instructions.<br><strong>3. Package the Item</strong> - Securely pack the item in its original packaging with all tags attached.<br><strong>4. Return Shipping</strong> - Send the package using your preferred shipping method to our store address or bring it to our physical location.<br><strong>5. Refund/Exchange</strong> - Once we receive and inspect the item, we\'ll process your refund or send out your exchange item.<br><br>Please note that shipping costs for returns are the responsibility of the customer unless the return is due to our error.',
            'category' => 'returns',
            'order' => 2,
            'is_active' => true
        ]);

        // Payments FAQs
        Faq::create([
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept the following payment methods:<br><br><strong>In-Store</strong>:<br>- Cash<br>- Credit/Debit Cards (Visa, Mastercard)<br>- QR payment (eSewa, Khalti, IME Pay)<br><br><strong>Online</strong>:<br>- Credit/Debit Cards<br>- eSewa<br>- Khalti<br>- IME Pay<br>- Connect IPS<br>- Bank transfer<br><br>All online transactions are secure and protected with industry-standard encryption.',
            'category' => 'payments',
            'order' => 1,
            'is_active' => true
        ]);

        Faq::create([
            'question' => 'Can I pay cash on delivery?',
            'answer' => 'Yes, we offer Cash on Delivery (COD) within Kathmandu Valley and select major cities in Nepal. Please note the following COD policies:<br><br>- COD is available for orders under NPR 20,000<br>- A valid phone number and complete address are required<br>- Government-issued ID may be required at the time of delivery for verification<br>- Our delivery personnel can provide change, but we recommend having the exact amount ready<br><br>COD may be temporarily suspended during peak seasons or in certain circumstances. Please check the availability of this option during checkout.',
            'category' => 'payments',
            'order' => 2,
            'is_active' => true
        ]);
    }
}
