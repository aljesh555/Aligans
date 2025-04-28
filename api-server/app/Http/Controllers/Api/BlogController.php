<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Get a list of published blog posts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $posts = BlogPost::published()
                ->paginate($perPage);

            // Transform image URLs to full URLs
            $posts->getCollection()->transform(function ($post) {
                $post->featured_image = $this->transformImage($post->featured_image);
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blog posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific blog post by slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        try {
            $post = BlogPost::where('slug', $slug)
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->firstOrFail();

            // Transform image URL to full URL
            $post->featured_image = $this->transformImage($post->featured_image);

            return response()->json([
                'success' => true,
                'data' => $post,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get the latest published blog posts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(Request $request)
    {
        try {
            $limit = $request->input('limit', 5);
            $posts = BlogPost::published()
                ->limit($limit)
                ->get();

            // Transform image URLs to full URLs
            $posts->transform(function ($post) {
                $post->featured_image = $this->transformImage($post->featured_image);
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch latest blog posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transform image path to full URL
     */
    private function transformImage($path)
    {
        if (!$path) return null;
        
        // If already a full URL, return as is
        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            return $path;
        }
        
        // Convert relative path to full URL
        return url('storage/' . $path);
    }
} 