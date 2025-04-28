<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HeroImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heroImages = HeroImage::orderBy('order')->get();
        return response()->json([
            'success' => true,
            'data' => $heroImages
        ]);
    }

    /**
     * Get active hero images.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveHeroImages()
    {
        $heroImages = HeroImage::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $heroImages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('hero-images', $imageName, 'public');
        }

        // Set order to last if not specified
        $order = $request->order;
        if (!$order) {
            $maxOrder = HeroImage::max('order') ?? 0;
            $order = $maxOrder + 1;
        }

        $heroImage = HeroImage::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'url' => $request->url,
            'button_text' => $request->button_text,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
            'order' => $order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hero image created successfully',
            'data' => $heroImage
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $heroImage = HeroImage::find($id);
        
        if (!$heroImage) {
            return response()->json([
                'success' => false,
                'message' => 'Hero image not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $heroImage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $heroImage = HeroImage::find($id);
        
        if (!$heroImage) {
            return response()->json([
                'success' => false,
                'message' => 'Hero image not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($heroImage->image_path) {
                Storage::disk('public')->delete($heroImage->image_path);
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('hero-images', $imageName, 'public');
            $heroImage->image_path = $imagePath;
        }

        // Update fields if they exist in the request
        if ($request->has('title')) {
            $heroImage->title = $request->title;
        }
        
        if ($request->has('description')) {
            $heroImage->description = $request->description;
        }
        
        if ($request->has('url')) {
            $heroImage->url = $request->url;
        }
        
        if ($request->has('button_text')) {
            $heroImage->button_text = $request->button_text;
        }
        
        if ($request->has('is_active')) {
            $heroImage->is_active = $request->is_active;
        }
        
        if ($request->has('order')) {
            $heroImage->order = $request->order;
        }

        $heroImage->save();

        return response()->json([
            'success' => true,
            'message' => 'Hero image updated successfully',
            'data' => $heroImage
        ]);
    }

    /**
     * Update the active state of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateActiveState(Request $request, $id)
    {
        $heroImage = HeroImage::find($id);
        
        if (!$heroImage) {
            return response()->json([
                'success' => false,
                'message' => 'Hero image not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $heroImage->is_active = $request->is_active;
        $heroImage->save();

        return response()->json([
            'success' => true,
            'message' => 'Hero image active state updated successfully',
            'data' => $heroImage
        ]);
    }

    /**
     * Reorder hero images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:hero_images,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->orders as $item) {
            HeroImage::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Hero images reordered successfully',
            'data' => HeroImage::orderBy('order')->get()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $heroImage = HeroImage::find($id);
        
        if (!$heroImage) {
            return response()->json([
                'success' => false,
                'message' => 'Hero image not found'
            ], 404);
        }

        // Delete image file from storage
        if ($heroImage->image_path) {
            Storage::disk('public')->delete($heroImage->image_path);
        }

        $heroImage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hero image deleted successfully'
        ]);
    }
}
