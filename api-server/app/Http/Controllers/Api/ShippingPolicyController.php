<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingPolicy;
use Illuminate\Http\Request;

class ShippingPolicyController extends Controller
{
    /**
     * Get active shipping policies
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShippingPolicies()
    {
        $shippingPolicies = ShippingPolicy::getActive();
        
        // If no active shipping policy found, create a default one
        if ($shippingPolicies->isEmpty()) {
            $defaultPolicy = ShippingPolicy::create([
                'title' => 'Shipping Policy',
                'content' => '<p>Default shipping policy content.</p>',
                'is_active' => true
            ]);
            
            $shippingPolicies = collect([$defaultPolicy]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $shippingPolicies
        ]);
    }
} 