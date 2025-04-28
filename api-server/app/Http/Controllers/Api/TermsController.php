<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Term;

class TermsController extends Controller
{
    /**
     * Get active terms and conditions
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTerms()
    {
        $terms = Term::getActive();
        
        if ($terms->isEmpty()) {
            // Create a default terms record
            $defaultTerm = Term::create([
                'title' => 'Terms & Conditions',
                'content' => '<h2>Welcome to Our Terms & Conditions</h2><p>This is a placeholder for your terms and conditions content. Please update it in the admin panel.</p>',
                'is_active' => true
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [$defaultTerm]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $terms
        ]);
    }
    
    /**
     * Update terms and conditions (admin only)
     */
    public function updateTerms(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Create new terms version
        $terms = Term::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => true
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Terms updated successfully',
            'data' => $terms
        ]);
    }
} 