<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Get social media links for the footer
     */
    public function getSocialMedia()
    {
        $socialMediaSettings = Setting::where('group', 'social_media')
            ->where('key', 'LIKE', 'social_media_%')
            ->get();
            
        $socialMedia = [];
        
        foreach ($socialMediaSettings as $setting) {
            $socialMedia[] = [
                'platform' => str_replace('social_media_', '', $setting->key),
                'name' => $setting->value['name'] ?? '',
                'url' => $setting->value['url'] ?? '#',
                'icon' => $setting->value['icon'] ?? '',
                'is_active' => $setting->value['is_active'] ?? true,
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $socialMedia
        ]);
    }
    
    /**
     * Store social media settings
     */
    public function storeSocialMediaSettings(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'name' => 'required|string',
            'url' => 'required|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $platform = $request->input('platform');
        $key = 'social_media_' . $platform;
        
        $value = [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'icon' => $request->input('icon'),
            'is_active' => $request->input('is_active', true),
        ];
        
        Setting::set($key, $value, 'json', 'social_media', 'Social media link for ' . $platform);
        
        return response()->json([
            'success' => true,
            'message' => 'Social media settings saved successfully'
        ]);
    }
    
    /**
     * Update social_links setting
     */
    public function updateSocialLinks(Request $request)
    {
        $request->validate([
            'facebook' => 'nullable|string|url',
            'instagram' => 'nullable|string|url',
            'twitter' => 'nullable|string|url',
            'youtube' => 'nullable|string|url',
        ]);
        
        $socialLinks = [
            'facebook' => $request->input('facebook', ''),
            'instagram' => $request->input('instagram', ''),
            'twitter' => $request->input('twitter', ''),
            'youtube' => $request->input('youtube', ''),
        ];
        
        Setting::set('social_links', $socialLinks, 'json', 'social', 'Social media links');
        
        return response()->json([
            'success' => true,
            'message' => 'Social media links updated successfully',
            'data' => $socialLinks
        ]);
    }
    
    /**
     * Get social_links setting
     */
    public function getSocialLinks()
    {
        // Check if we have a setting with repeater format first
        $socialLinksSetting = Setting::where('key', 'social_links')->first();
        
        if ($socialLinksSetting) {
            $socialLinks = $socialLinksSetting->value;
            
            // Handle different possible formats
            if (is_string($socialLinks) && json_decode($socialLinks) !== null) {
            $socialLinks = json_decode($socialLinks, true);
        }
            
            // Check if the format is repeater style
            if (isset($socialLinks[0]) && isset($socialLinks[0]['key']) && isset($socialLinks[0]['value'])) {
                // It's already in repeater format, return it directly
                return response()->json([
                    'success' => true,
                    'data' => [
                        'social_links' => $socialLinks
                    ]
                ]);
            } else {
                // It's in key-value object format, convert to repeater format
                $repeaterFormat = [];
                foreach ($socialLinks as $platform => $url) {
                    if (!empty($url)) {
                        $repeaterFormat[] = [
                            'key' => ucfirst($platform),
                            'value' => $url
                        ];
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'social_links' => $repeaterFormat
                    ]
                ]);
            }
        }
        
        // Fallback to default social links in repeater format
        $defaultLinks = [
            ['key' => 'Facebook', 'value' => 'facebook.com'],
            ['key' => 'Instagram', 'value' => 'https://www.instagram.com/'],
            ['key' => 'Twitter', 'value' => 'https://twitter.com'],
            ['key' => 'Yutube', 'value' => 'https://www.youtube.com/']
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'social_links' => $defaultLinks
            ]
        ]);
    }

    /**
     * Get customer care settings
     */
    public function getCustomerCare()
    {
        try {
            // Fetch the customer_care setting
            $customerCareSetting = Setting::where('key', 'customer_care')->first();
            
            $data = [];
            
            if ($customerCareSetting) {
                // If the setting exists, include it in the response
                // Ensure the value is properly decoded if it's a JSON string
                if (is_string($customerCareSetting->value) && json_decode($customerCareSetting->value) !== null) {
                    $data['customer_care'] = json_decode($customerCareSetting->value, true);
                } else {
                    $data['customer_care'] = $customerCareSetting->value;
                }
            } else {
                // If setting doesn't exist, set default values
                $data['customer_care'] = [
                    'timings' => '10AM to 10PM',
                    'whatsapp' => '9819963606',
                    'email' => 'care@yourstore.com',
                    'address' => 'santingar,Baneshwor',
                    'phone' => '+977-1-4444444',
                    'viber' => '+977-9800000000'
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getCustomerCare: ' . $e->getMessage());
            
            // Return a fallback response with default values
            return response()->json([
                'success' => true,
                'data' => [
                    'customer_care' => [
                        'timings' => '10AM to 10PM',
                        'whatsapp' => '9819963606',
                        'email' => 'care@yourstore.com',
                        'address' => 'santingar,Baneshwor',
                        'phone' => '+977-1-4444444',
                        'viber' => '+977-9800000000'
                    ]
                ]
            ]);
        }
    }

    /**
     * Update customer care settings
     */
    public function updateCustomerCare(Request $request)
    {
        $request->validate([
            'timings' => 'required|string',
            'whatsapp' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'viber' => 'nullable|string'
        ]);

        // Prepare the customer care data
        $customerCareData = [
            'timings' => $request->input('timings'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'viber' => $request->input('viber')
        ];

        // Update or create the single customer_care setting
        Setting::set(
            'customer_care',
            $customerCareData,
            'json',
            'general',
            'Customer care contact information'
        );

        return response()->json([
            'success' => true,
            'message' => 'Customer care settings updated successfully',
            'data' => ['customer_care' => $customerCareData]
        ]);
    }

    /**
     * Get header announcement message
     */
    public function getHeaderAnnouncement()
    {
        $setting = Setting::where('key', 'announcement_message')->first();
        
        $defaultMessage = 'Free shipping on orders over $75 | Same-day dispatch before 2pm';
        
        if (!$setting) {
            return response()->json([
                'success' => true,
                'data' => $defaultMessage
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $setting->value
        ]);
    }

    /**
     * Update header announcement message
     */
    public function updateHeaderAnnouncement(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        Setting::set(
            'announcement_message',
            $request->input('message'),
            'string',
            'appearance',
            'Header announcement message'
        );

        return response()->json([
            'success' => true,
            'message' => 'Header announcement updated successfully',
            'data' => $request->input('message')
        ]);
    }

    /**
     * Get message setting
     */
    public function getMessage()
    {
        $setting = Setting::where('key', 'message')->first();
        
        $message = 'Your ultimate destination for cricket equipment and apparel. We offer high-quality products for professional and amateur players alike.';
        
        if ($setting) {
            // Extract the value from the setting
            $value = $setting->value;
            
            // Handle case where value might be JSON
            if (is_string($value) && (strpos($value, '{') === 0 || strpos($value, '"') === 0)) {
                try {
                    $decoded = json_decode($value, true);
                    // If successfully decoded to non-null, use it
                    if ($decoded !== null) {
                        $value = $decoded;
                    }
                } catch (\Exception $e) {
                    // Keep the original value if JSON parsing fails
                }
            }
            
            $message = $value;
        }
        
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Get Terms & Conditions
     */
    public function getTermsConditions()
    {
        $setting = Setting::where('key', 'terms_conditions')->first();
        
        $defaultTerms = [
            'title' => 'Terms & Conditions',
            'last_updated' => date('F j, Y'),
            'content' => '<p>Default terms and conditions content.</p>'
        ];
        
        if (!$setting) {
            return response()->json([
                'success' => true,
                'data' => $defaultTerms
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $setting->value
        ]);
    }

    /**
     * Update Terms & Conditions
     */
    public function updateTermsConditions(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        
        $termsData = [
            'title' => $request->input('title'),
            'last_updated' => date('F j, Y'),
            'content' => $request->input('content')
        ];
        
        Setting::set(
            'terms_conditions',
            $termsData,
            'json',
            'legal',
            'Website Terms and Conditions'
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Terms and conditions updated successfully',
            'data' => $termsData
        ]);
    }

    /**
     * Get footer about text setting
     */
    public function getFooterAboutText()
    {
        try {
            $setting = Setting::where('key', 'footer_about_text')->first();
            
            if ($setting) {
                // Get the raw value
                $value = $setting->value;
                
                // Handle double-encoded JSON string (common with Filament forms)
                // First, try to decode once
                if (is_string($value)) {
                    try {
                        $decoded1 = json_decode($value, true);
                        
                        // If still a string and looks like a JSON string, decode again
                        if (is_string($decoded1) && 
                            (str_starts_with($decoded1, '"') && str_ends_with($decoded1, '"'))) {
                            try {
                                $decoded2 = json_decode($decoded1, true);
                                $value = $decoded2;
                            } catch (\Exception $e) {
                                // If second decoding fails, keep the first decoded value
                                $value = $decoded1;
                            }
                        } else {
                            // Use the first decoded value
                            $value = $decoded1;
                        }
                    } catch (\Exception $e) {
                        // Keep original value if decoding fails
                        \Log::error('Failed to decode JSON value: ' . $e->getMessage());
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $value
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching footer about text: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get setting by key
     */
    public function getByKey($key)
    {
        try {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Get the raw value
                $value = $setting->value;
                
                // Handle double-encoded JSON string (common with Filament forms)
                // First, try to decode once
                if (is_string($value)) {
                    try {
                        $decoded1 = json_decode($value, true);
                        
                        // If still a string and looks like a JSON string, decode again
                        if (is_string($decoded1) && 
                            (str_starts_with($decoded1, '"') && str_ends_with($decoded1, '"'))) {
                            try {
                                $decoded2 = json_decode($decoded1, true);
                                $value = $decoded2;
                            } catch (\Exception $e) {
                                // If second decoding fails, keep the first decoded value
                                $value = $decoded1;
                            }
                        } else {
                            // Use the first decoded value
                            $value = $decoded1;
                        }
                    } catch (\Exception $e) {
                        // Keep original value if decoding fails
                        \Log::error('Failed to decode JSON value: ' . $e->getMessage());
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'key' => $setting->key,
                    'data' => $value,
                    'type' => $setting->type,
                    'group' => $setting->group
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching setting by key: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logo from settings
     */
    public function getLogo()
    {
        try {
            $setting = Setting::where('key', 'logo')->first();
            
            if ($setting) {
                // Get the raw value
                $value = $setting->value;
                $baseUrl = url('/');
                
                // Handle quoted values
                if (is_string($value) && strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) {
                    $value = substr($value, 1, -1); // Remove surrounding quotes
                }
                
                // Handle new format with UUID key in JSON object 
                // (Filament LiveWire upload format)
                if (is_array($value) && !empty($value)) {
                    // Get the first value from the array (the file path)
                    $value = reset($value);
                }
                
                // Create a logo response similar to what the LogoController returns
                $logo = [
                    'id' => 1,
                    'image_path' => $value,
                    'image_url' => $baseUrl . '/storage/' . $value,
                    'is_active' => true,
                    'file_exists' => true,
                    'base64_image' => null
                ];
                
                return response()->json($logo);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Logo not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error fetching logo setting: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
