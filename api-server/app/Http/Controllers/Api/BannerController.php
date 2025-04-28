<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if we should return all banners or only active ones
        if ($request->has('all')) {
            // Return all banners for admin
            $banners = Banner::select('*')->orderBy('order', 'asc')->get();
        } else {
            // Return only active banners for public
            $banners = Banner::where('is_active', true)
                ->whereRaw('(starts_at IS NULL OR starts_at <= CURDATE())')
                ->whereRaw('(ends_at IS NULL OR ends_at >= CURDATE())')
                ->orderBy('order', 'asc')
                ->get();
        }
        
        return response()->json($banners);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Banner store request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at'
        ]);

        if ($validator->fails()) {
            \Log::error('Banner validation failed:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('banners', $imageName, 'public');
            }

            // Prepare data for creation
            $bannerData = $request->except('image');
            $bannerData['image_url'] = $imagePath ? asset('storage/' . $imagePath) : null;
            
            // Set is_active field properly
            $bannerData['is_active'] = $request->has('is_active') && $request->is_active ? true : false;
            
            \Log::info('Creating banner with data:', $bannerData);
            
            $banner = Banner::create($bannerData);
            \Log::info('Banner created:', $banner->toArray());

            return response()->json($banner, 201);
        } catch (\Exception $e) {
            \Log::error('Error creating banner:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to create banner: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        \Log::info('Banner update request for ID ' . $id . ':', $request->all());
        
        try {
            $banner = Banner::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_link' => 'nullable|string|max:255',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'starts_at' => 'nullable|date',
                'ends_at' => 'nullable|date|after_or_equal:starts_at'
            ]);

            if ($validator->fails()) {
                \Log::error('Banner update validation failed:', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($banner->image_url) {
                    $oldPath = str_replace(asset('storage/'), '', $banner->image_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Store new image
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('banners', $imageName, 'public');
                
                $banner->image_url = asset('storage/' . $imagePath);
            }

            // Convert is_active to boolean
            if ($request->has('is_active')) {
                $banner->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            }

            // Update other fields
            $banner->fill($request->except(['image', 'image_url', 'is_active']));
            
            \Log::info('Saving banner with data:', $banner->toArray());
            $banner->save();
            \Log::info('Banner updated successfully');

            return response()->json($banner);
        } catch (\Exception $e) {
            \Log::error('Error updating banner:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update banner: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        
        // Delete image file if exists
        if ($banner->image_url) {
            $imagePath = str_replace(asset('storage/'), '', $banner->image_url);
            Storage::disk('public')->delete($imagePath);
        }
        
        $banner->delete();

        return response()->json(null, 204);
    }
}
