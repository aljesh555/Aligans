<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    /**
     * Get a static page by slug
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        try {
            $page = StaticPage::where('slug', $slug)
                ->where('is_active', true)
                ->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active static pages
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $pages = StaticPage::where('is_active', true)
                ->orderBy('title')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pages: ' . $e->getMessage()
            ], 500);
        }
    }
} 