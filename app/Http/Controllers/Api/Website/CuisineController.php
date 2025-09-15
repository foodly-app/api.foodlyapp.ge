<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Cuisine\Cuisine;
use App\Http\Resources\Cuisine\CuisineResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CuisineController extends Controller
{
    /**
     * Get cuisines list for Website platform
     */
    public function index(Request $request)
    {
        try {
            $cuisines = Cuisine::where('status', 'active')
                ->orderBy('rank', 'asc')
                ->paginate(12);

            if ($cuisines->isEmpty()) {
                return response()->json(['error' => 'No cuisines found'], 404);
            }

            return CuisineResource::collection($cuisines);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch cuisines',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single Cuisine by slug
     */
    public function showBySlug($slug)
    {
        try {
            $cuisine = Cuisine::where('slug', $slug)->firstOrFail();
            return new CuisineResource($cuisine);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cuisine not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch Cuisine',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get restaurants by Cuisine slug
    public function restaurantsByCuisine($slug)
    {
        try {
            $cuisine = Cuisine::where('slug', $slug)->firstOrFail();

            $restaurants = $cuisine->restaurants()
                ->where('restaurants.status', 'active')
                ->orderBy('restaurants.name')
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this cuisine'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cuisine not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get top 10 restaurants by Cuisine slug
    public function top10RestaurantsByCuisine($slug)
    {
        try {
            $cuisine = Cuisine::where('slug', $slug)->firstOrFail();

            $restaurants = $cuisine->restaurants()
                ->where('restaurants.status', 'active')
                ->orderBy('restaurants.rating', 'desc')
                ->take(10)
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this cuisine'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Cuisine not found'], 404);
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
            'message' => 'Website cuisines test endpoint working - no auth required',
            'timestamp' => now()->toISOString(),
            'endpoint' => 'GET /api/website/cuisines/test'
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