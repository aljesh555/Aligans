<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Get the About Us content from static pages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $aboutUs = StaticPage::where('slug', 'about-us')
                ->where('is_active', true)
                ->first();

            if (!$aboutUs) {
                return response()->json([
                    'success' => false,
                    'title' => 'About Us',
                    'content' => '<p>Welcome to Aligans. We are committed to providing high-quality sports equipment and apparel.</p>',
                    'message' => 'About Us content not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $aboutUs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching About Us content: ' . $e->getMessage(),
                'title' => 'About Us',
                'content' => '<p>Welcome to Aligans. We are committed to providing high-quality sports equipment and apparel.</p>'
            ], 500);
        }
    }
} 