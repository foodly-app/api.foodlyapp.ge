<?php

namespace App\Http\Controllers\Api\Kiosk;

use App\Http\Controllers\Controller;
use App\Models\Spot\Spot;
use App\Http\Resources\Spot\SpotResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SpotController extends Controller
{
    /**
     * Get spots list for Kiosk platform
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
     * Get a single Spot by slug
     */
    public function showBySlug($slug)
    {
        try {
            $spot = Spot::where('slug', $slug)->firstOrFail();
            return new SpotResource($spot);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Spot not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch Spot',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get restaurants by Spot slug
    public function restaurantsBySpot($slug)
    {
        try {
            $spot = Spot::where('slug', $slug)->firstOrFail();

            $restaurants = $spot->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_spot.rank', 'asc')
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this spot'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Spot not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    
    // Get top 10 restaurants by Spot slug
    public function top10RestaurantsBySpot($slug)
    {
        try {
            $spot = Spot::where('slug', $slug)->firstOrFail();

            $restaurants = $spot->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_spot.rank', 'asc')
                ->take(10)
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this spot'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Spot not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
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
            'message' => 'Kiosk spots test endpoint working - no auth required',
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/kiosk/spots/test'
        ]);
    }

  
}
