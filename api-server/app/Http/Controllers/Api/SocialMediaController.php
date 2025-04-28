<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
    /**
     * Get all active social media links
     */
    public function getActive()
    {
        $socialMedia = SocialMedia::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $socialMedia
        ]);
    }
    
    /**
     * Get all social media links (for admin)
     */
    public function index()
    {
        $socialMedia = SocialMedia::ordered()->get();
        
        return response()->json([
            'success' => true,
            'data' => $socialMedia
        ]);
    }
    
    /**
     * Store a new social media link
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|max:255|unique:social_media',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $socialMedia = SocialMedia::create($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Social media link created successfully',
            'data' => $socialMedia
        ], 201);
    }
    
    /**
     * Get a specific social media link
     */
    public function show($id)
    {
        $socialMedia = SocialMedia::find($id);
        
        if (!$socialMedia) {
            return response()->json([
                'success' => false,
                'message' => 'Social media link not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $socialMedia
        ]);
    }
    
    /**
     * Update a social media link
     */
    public function update(Request $request, $id)
    {
        $socialMedia = SocialMedia::find($id);
        
        if (!$socialMedia) {
            return response()->json([
                'success' => false,
                'message' => 'Social media link not found'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'platform' => 'string|max:255|unique:social_media,platform,' . $id,
            'name' => 'string|max:255',
            'url' => 'url|max:255',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $socialMedia->update($request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Social media link updated successfully',
            'data' => $socialMedia
        ]);
    }
    
    /**
     * Delete a social media link
     */
    public function destroy($id)
    {
        $socialMedia = SocialMedia::find($id);
        
        if (!$socialMedia) {
            return response()->json([
                'success' => false,
                'message' => 'Social media link not found'
            ], 404);
        }
        
        $socialMedia->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Social media link deleted successfully'
        ]);
    }
}
