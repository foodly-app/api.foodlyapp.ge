<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Space\Space;
use App\Http\Resources\Space\SpaceResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    /**
     * Get spaces list for Website platform
     */
    public function index(Request $request)
    {
        try {
            $spaces = Space::where('status', 'active')
                ->orderBy('rank', 'asc')
                ->paginate(12);

            if ($spaces->isEmpty()) {
                return response()->json(['error' => 'No spaces found'], 404);
            }

            return SpaceResource::collection($spaces);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch spaces',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single Space by slug
     */
    public function showBySlug($slug)
    {
        try {
            $space = Space::where('slug', $slug)->firstOrFail();
            return new SpaceResource($space);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Space not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch Space',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get restaurants by Space slug
    public function restaurantsBySpace($slug)
    {
        try {
            $space = Space::where('slug', $slug)->firstOrFail();

            $restaurants = $space->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_space.rank', 'asc')
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this space'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Space not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get top 10 restaurants by Space slug
    public function top10RestaurantsBySpace($slug)
    {
        try {
            $space = Space::where('slug', $slug)->firstOrFail();

            $restaurants = $space->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_space.rank', 'asc')
                ->take(10)
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this space'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Space not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test endpoint for Website platform (no authentication required)
     */
    public function test(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'platform' => 'website',
            'locale' => app()->getLocale(),
            'message' => 'Website spaces test endpoint working - no auth required',
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/website/spaces/test'
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