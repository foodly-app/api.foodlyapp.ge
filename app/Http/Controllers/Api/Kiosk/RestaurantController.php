<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Get restaurants list for Kiosk platform
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'kiosk',
            'locale' => app()->getLocale(),
            'message' => 'Kiosk restaurants endpoint working'
        ]);
    }

    /**
     * Test endpoint for Kiosk platform (no authentication required)
     */
    public function test(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'kiosk',
            'locale' => app()->getLocale(),
            'message' => 'Kiosk test endpoint working - no auth required',
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/kiosk/test'
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
        //
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
