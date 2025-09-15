<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\City\City;
use App\Http\Resources\City\CityResource;
use App\Http\Resources\City\CityShortResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Get cities list for Website platform
     */
    public function index(Request $request)
    {
        try {
            $cities = City::where('status', 'active')
                ->orderBy('rank', 'asc')
                ->paginate(12);

            if ($cities->isEmpty()) {
                return response()->json(['error' => 'No cities found'], 404);
            }

            return CityShortResource::collection($cities);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch cities',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single City by slug
     */
    public function showBySlug($slug)
    {
        try {
            $city = City::where('slug', $slug)->firstOrFail();
            return new CityResource($city);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'City not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch City',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get restaurants by City slug
    public function restaurantsByCity($slug)
    {
        try {
            $city = City::where('slug', $slug)->firstOrFail();

            $restaurants = $city->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('city_restaurant.rank', 'asc')
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this city'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'City not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get top 10 restaurants by City slug
    public function top10RestaurantsByCity($slug)
    {
        try {
            $city = City::where('slug', $slug)->firstOrFail();

            $restaurants = $city->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('city_restaurant.rank', 'asc')
                ->take(10)
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this city'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'City not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch top restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}