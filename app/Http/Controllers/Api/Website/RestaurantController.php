<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Get restaurants list for Website platform
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'website',
            'locale' => app()->getLocale(),
            'message' => 'Website restaurants endpoint working - ' . app()->getLocale() . ' locale',
            'timestamp' => now()->toISOString(),
            'data' => [
                'restaurants' => [],
                'authenticated_user' => $request->user()
            ],
            'endpoint' => 'GET /api/website/restaurants'
        ]);
    }

    /**
     * Test endpoint for Website platform (no authentication required)
     */
    public function test(Request $request)
    {
        $locale = app()->getLocale();
        
        // Locale-specific messages
        $messages = [
            'ka' => 'Website test endpoint მუშაობს - ქართული ლოკალით',
            'en' => 'Website test endpoint working - English locale', 
            'ru' => 'Website test endpoint работает - Russian locale',
            'tr' => 'Website test endpoint çalışıyor - Turkish locale'
        ];

        return response()->json([
            'status' => 'success',
            'platform' => 'website',
            'locale' => $locale,
            'message' => $messages[$locale] ?? $messages['en'],
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/website/test'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'website',
            'locale' => app()->getLocale(),
            'message' => 'Website restaurant details',
            'data' => [
                'restaurant_id' => $id,
                'restaurant' => null // Will be populated with actual data
            ],
            'endpoint' => 'GET /api/website/restaurants/' . $id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}