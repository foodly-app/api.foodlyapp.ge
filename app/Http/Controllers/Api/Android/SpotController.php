<?php

namespace App\Http\Controllers\Api\Android;

use App\Http\Controllers\Controller;
use App\Models\Spot\Spot;
use App\Http\Resources\Spot\SpotResource;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    /**
     * Get spots list for Android platform
     */
    public function index(Request $request)
    {
        try {
            $spots = Spot::where('status', 'active')
                ->orderBy('rank', 'asc')
                ->paginate(12);

            if ($spots->isEmpty()) {
                return response()->json(['error' => 'No spots found'], 404);
            }

            return SpotResource::collection($spots);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch spots',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint for Android platform (no authentication required)
     */
    public function test(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'android',
            'locale' => app()->getLocale(),
            'message' => 'Android spots test endpoint working - no auth required',
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/android/spots/test'
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
