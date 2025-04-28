<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Show the customer care settings form
     */
    public function showCustomerCareForm()
    {
        // Get existing customer care settings
        $customerCareSetting = Setting::where('key', 'customer_care')->first();
        
        $customerCare = [];
        
        if ($customerCareSetting) {
            // Parse JSON value if needed
            $value = $customerCareSetting->value;
            if (is_string($value)) {
                $customerCare = json_decode($value, true) ?? [];
            } else {
                $customerCare = $value;
            }
        }
        
        // Set default values for form fields if not present
        $customerCare = array_merge([
            'timings' => '',
            'phone' => '',
            'whatsapp' => '',
            'email' => '',
            'address' => '',
            'viber' => ''
        ], $customerCare);
        
        return view('admin.settings.customer-care', compact('customerCare'));
    }
    
    /**
     * Update customer care settings
     */
    public function updateCustomerCare(Request $request)
    {
        $request->validate([
            'timings' => 'required|string',
            'phone' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'viber' => 'nullable|string'
        ]);
        
        // Prepare customer care data from form inputs
        $customerCareData = [
            'timings' => $request->input('timings'),
            'phone' => $request->input('phone'),
            'whatsapp' => $request->input('whatsapp'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'viber' => $request->input('viber')
        ];
        
        // Update or create the customer_care setting
        Setting::set(
            'customer_care',
            $customerCareData,
            'json',
            'general',
            'Customer care contact information'
        );
        
        return redirect()->back()->with('success', 'Customer care settings updated successfully');
    }

    /**
     * Show the terms & conditions settings form
     */
    public function showTermsForm()
    {
        // Get existing terms & conditions settings
        $termsSetting = Setting::where('key', 'terms_conditions')->first();
        
        $terms = [];
        
        if ($termsSetting) {
            // Parse JSON value if needed
            $value = $termsSetting->value;
            if (is_string($value)) {
                $terms = json_decode($value, true) ?? [];
            } else {
                $terms = $value;
            }
        }
        
        // Set default values for form fields if not present
        $terms = array_merge([
            'title' => 'Terms & Conditions',
            'last_updated' => date('F j, Y'),
            'content' => '<p>Default terms and conditions content.</p>'
        ], $terms);
        
        return view('admin.settings.terms', compact('terms'));
    }

    /**
     * Update terms & conditions settings
     */
    public function updateTerms(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);
        
        // Prepare terms data from form inputs
        $termsData = [
            'title' => $request->input('title'),
            'last_updated' => date('F j, Y'),
            'content' => $request->input('content')
        ];
        
        // Update or create the terms_conditions setting
        Setting::set(
            'terms_conditions',
            $termsData,
            'json',
            'legal',
            'Website Terms and Conditions'
        );
        
        return redirect()->back()->with('success', 'Terms and conditions updated successfully');
    }

    /**
     * Show the form to edit header announcement
     */
    public function showHeaderAnnouncementForm()
    {
        $setting = Setting::where('key', 'announcement_message')->first();
        
        $message = $setting ? $setting->value : 'Free shipping on orders over $75 | Same-day dispatch before 2pm';
        
        return view('admin.settings.header_announcement', compact('message'));
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
            'Header announcement message displayed at the top of the website'
        );

        return redirect()->back()->with('success', 'Header announcement updated successfully');
    }
} 