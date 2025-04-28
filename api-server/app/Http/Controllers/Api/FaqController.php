<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Get all active FAQs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $faqs = Faq::where('is_active', true)
                  ->orderBy('order')
                  ->get();

        return response()->json($faqs);
    }

    /**
     * Get FAQs by category
     *
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory($category)
    {
        $faqs = Faq::where('is_active', true)
                  ->where('category', $category)
                  ->orderBy('order')
                  ->get();

        return response()->json($faqs);
    }

    /**
     * Get all unique FAQ categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = Faq::where('is_active', true)
                       ->select('category')
                       ->distinct()
                       ->orderBy('category')
                       ->pluck('category');

        return response()->json($categories);
    }
}
