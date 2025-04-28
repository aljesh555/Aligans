<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Get active privacy policies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrivacyPolicies()
    {
        $privacyPolicies = PrivacyPolicy::getActive();
        
        // If no active privacy policy found, create a default one
        if ($privacyPolicies->isEmpty()) {
            $defaultPolicy = PrivacyPolicy::create([
                'title' => 'Privacy Policy',
                'content' => '<p>Default privacy policy content.</p>',
                'is_active' => true
            ]);
            
            $privacyPolicies = collect([$defaultPolicy]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $privacyPolicies
        ]);
    }
} 