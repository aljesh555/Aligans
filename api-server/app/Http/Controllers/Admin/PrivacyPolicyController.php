<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    /**
     * Display the privacy policy management page
     */
    public function index()
    {
        $activePrivacyPolicies = PrivacyPolicy::getActive();
        
        // If no active privacy policy found, create default one
        if ($activePrivacyPolicies->isEmpty()) {
            $defaultPolicy = PrivacyPolicy::create([
                'title' => 'Privacy Policy',
                'content' => '<p>Default privacy policy content.</p>',
                'is_active' => true
            ]);
            $activePrivacyPolicies = collect([$defaultPolicy]);
        }
        
        return view('admin.privacy-policy.index', compact('activePrivacyPolicies'));
    }
    
    /**
     * Show the form for editing privacy policy
     */
    public function edit()
    {
        // Get latest privacy policy for editing template
        $privacyPolicy = PrivacyPolicy::latest()->first();
        
        // If no privacy policy found, create default one
        if (!$privacyPolicy) {
            $privacyPolicy = PrivacyPolicy::create([
                'title' => 'Privacy Policy',
                'content' => '<p>Default privacy policy content.</p>',
                'is_active' => true
            ]);
        }
        
        return view('admin.privacy-policy.edit', compact('privacyPolicy'));
    }
    
    /**
     * Update the privacy policy
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Deactivate all existing privacy policies
        PrivacyPolicy::where('is_active', true)->update(['is_active' => false]);
        
        // Create new privacy policy version
        PrivacyPolicy::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true
        ]);
        
        return redirect()->route('admin.privacy-policy.index')
            ->with('success', 'Privacy policy updated successfully.');
    }
    
    /**
     * View privacy policy history
     */
    public function history()
    {
        $privacyPolicyHistory = PrivacyPolicy::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.privacy-policy.history', compact('privacyPolicyHistory'));
    }
} 