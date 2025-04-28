<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    /**
     * Display the active logo.
     */
    public function getActive()
    {
        \Log::info('Fetching active logo');
        
        $logo = Logo::where('is_active', true)->first();
        
        if (!$logo) {
            \Log::warning('No active logo found');
            return response()->json(['message' => 'No active logo found'], 404);
        }
        
        // Validate if the file actually exists (only needed if base64 is not available)
        if (!$logo->base64_image) {
            $path = str_replace('storage/', '', $logo->image_path);
            $exists = Storage::disk('public')->exists($path);
            
            \Log::info('Active logo found', [
                'logo_id' => $logo->id, 
                'path' => $logo->image_path,
                'file_exists' => $exists,
                'has_base64' => !empty($logo->base64_image),
                'full_server_path' => Storage::disk('public')->path($path)
            ]);
            
            // Store the file exists status
            $logo->file_exists = $exists;
        } else {
            \Log::info('Active logo found with base64 data', [
                'logo_id' => $logo->id, 
                'has_base64' => true
            ]);
            $logo->file_exists = true;
        }
        
        // Add the image_url to the response
        $logo->makeVisible('image_url');
        
        return response()->json($logo);
    }
    
    /**
     * Display a listing of all logos.
     */
    public function index()
    {
        $logos = Logo::all();
        return response()->json($logos);
    }

    /**
     * Store a newly created logo.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Upload the file
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            
            // Convert the image to base64
            $base64Image = 'data:' . $file->getMimeType() . ';base64,' . 
                           base64_encode(file_get_contents($file->getPathname()));
            
            // Create the logo record
            $logo = Logo::create([
                'image_path' => 'storage/' . $path,
                'base64_image' => $base64Image,
                'is_active' => $request->input('is_active', false)
            ]);
            
            // If this logo is set as active, deactivate all others
            if ($request->input('is_active', false)) {
                Logo::setActive($logo->id);
            }
            
            return response()->json($logo, 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store logo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified logo.
     */
    public function show($id)
    {
        $logo = Logo::findOrFail($id);
        return response()->json($logo);
    }

    /**
     * Update the specified logo.
     */
    public function update(Request $request, $id)
    {
        $logo = Logo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $logoData = [];
            
            // Update image only if a new one is uploaded
            if ($request->hasFile('logo')) {
                // Delete old file if it exists
                if ($logo->image_path) {
                    $oldPath = str_replace('storage/', '', $logo->image_path);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                // Upload new file
                $file = $request->file('logo');
                $path = $file->store('logos', 'public');
                $logoData['image_path'] = 'storage/' . $path;
                
                // Convert the new image to base64
                $logoData['base64_image'] = 'data:' . $file->getMimeType() . ';base64,' . 
                                           base64_encode(file_get_contents($file->getPathname()));
            }
            
            if ($request->has('is_active')) {
                $logoData['is_active'] = $request->input('is_active');
            }

            $logo->update($logoData);
            
            // If this logo is set as active, deactivate all others
            if ($request->has('is_active') && $request->input('is_active')) {
                Logo::setActive($logo->id);
            }
            
            return response()->json($logo);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update logo: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Set the specified logo as active.
     */
    public function setActive($id)
    {
        try {
            $logo = Logo::setActive($id);
            return response()->json($logo);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to activate logo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified logo.
     */
    public function destroy($id)
    {
        try {
            $logo = Logo::findOrFail($id);
            
            // Delete the file
            if ($logo->image_path) {
                $path = str_replace('storage/', '', $logo->image_path);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
            
            $logo->delete();
            
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete logo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Convert existing logos to base64.
     * This is a one-time command to update existing logos.
     */
    public function convertToBase64()
    {
        try {
            \Log::info('Starting base64 conversion for logos');
            $logos = Logo::whereNull('base64_image')->get();
            \Log::info('Found ' . $logos->count() . ' logos to convert');
            
            $count = 0;
            $errors = [];
            
            foreach ($logos as $logo) {
                try {
                    \Log::info('Processing logo #' . $logo->id . ' with path ' . $logo->image_path);
                    // Get the file path
                    $path = str_replace('storage/', '', $logo->image_path);
                    \Log::info('Checking file: ' . $path);
                    
                    if (Storage::disk('public')->exists($path)) {
                        \Log::info('File exists, converting to base64');
                        // Get file content and mime type
                        $content = Storage::disk('public')->get($path);
                        $mime = Storage::disk('public')->mimeType($path);
                        
                        // Convert to base64
                        $base64 = 'data:' . $mime . ';base64,' . base64_encode($content);
                        
                        // Update the logo
                        $logo->update([
                            'base64_image' => $base64
                        ]);
                        
                        \Log::info('Successfully converted logo #' . $logo->id . ' to base64');
                        $count++;
                    } else {
                        \Log::warning('File does not exist for logo #' . $logo->id);
                        $errors[] = 'Logo #' . $logo->id . ': File not found at ' . $path;
                    }
                } catch (\Exception $e) {
                    $errorMsg = 'Failed to convert logo #' . $logo->id . ' to base64: ' . $e->getMessage();
                    \Log::error($errorMsg);
                    $errors[] = $errorMsg;
                }
            }
            
            \Log::info('Conversion complete: ' . $count . ' logos converted');
            
            return response()->json([
                'message' => $count . ' logos converted to base64',
                'converted' => $count,
                'total' => $logos->count(),
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in convertToBase64: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error converting logos to base64: ' . $e->getMessage()
            ], 500);
        }
    }
} 