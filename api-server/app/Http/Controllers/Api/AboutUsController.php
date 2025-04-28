<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    /**
     * Get the active About Us content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $aboutUs = AboutUs::getActive();

        if (!$aboutUs) {
            return response()->json([
                'title' => 'About Us',
                'content' => '<p>Welcome to Aligans. We are committed to providing high-quality sports equipment and apparel.</p>',
                'mission' => 'Our mission is to provide quality sports equipment for athletes of all levels.',
                'vision' => 'To be the leading provider of sports equipment in our region.',
                'values' => 'Quality, Customer Satisfaction, Integrity',
                'team' => []
            ]);
        }

        return response()->json($aboutUs);
    }
} 