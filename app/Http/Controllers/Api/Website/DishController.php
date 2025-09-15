<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Dish\Dish;
use App\Http\Resources\Dish\DishResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Get dishes list for Website platform
     */
    public function index(Request $request)
    {
        try {
            $dishes = Dish::where('status', 'active')
                ->orderBy('rank', 'asc')
                ->paginate(12);

            if ($dishes->isEmpty()) {
                return response()->json(['error' => 'No dishes found'], 404);
            }

            return DishResource::collection($dishes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch dishes',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single Dish by slug
     */
    public function showBySlug($slug)
    {
        try {
            $dish = Dish::where('slug', $slug)->firstOrFail();
            return new DishResource($dish);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Dish not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch Dish',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get restaurants by Dish slug
    public function restaurantsByDish($slug)
    {
        try {
            $dish = Dish::where('slug', $slug)->firstOrFail();

            $restaurants = $dish->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_dish.rank', 'asc')
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this dish'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Dish not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get top 10 restaurants by Dish slug
    public function top10RestaurantsByDish($slug)
    {
        try {
            $dish = Dish::where('slug', $slug)->firstOrFail();

            $restaurants = $dish->restaurants()
                ->wherePivot('status', 'active')
                ->where('restaurants.status', 'active')
                ->orderBy('restaurant_dish.rank', 'asc')
                ->take(10)
                ->get();

            if ($restaurants->isEmpty()) {
                return response()->json(['error' => 'No restaurants found for this dish'], 404);
            }

            return RestaurantShortResource::collection($restaurants);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Dish not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch top restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
