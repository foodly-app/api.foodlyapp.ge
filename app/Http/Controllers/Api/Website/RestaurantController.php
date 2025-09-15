<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant\Restaurant;
use App\Http\Resources\Restaurant\RestaurantResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use App\Http\Resources\Restaurant\RestaurantDetailsResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RestaurantController extends Controller
{
    /**
     * Get all restaurants with filtering, sorting and pagination for Website platform
     * 
     * Supported query params:
     * - search={text}          → Search in name and description
     * - category={slug}        → Filter by category slug
     * - city={city_name}       → Filter by city
     * - sort={field}_{dir}     → Sorting, e.g: rank_asc, discount_rate_desc
     * - per_page={number}      → Items per page
     */
    public function index(Request $request)
    {
        try {
            // Parameters
            $perPage = (int) $request->query('per_page', 20);
            $sort = $request->query('sort', 'rank_asc');
            [$sortField, $sortDir] = explode('_', $sort) + [1 => 'asc'];

            // Query builder - only active restaurants
            $query = Restaurant::with(['translations'])
                ->where('status', Restaurant::STATUS_ACTIVE);

            // 1) Search by name or description
            if ($search = $request->query('search')) {
                $query->whereHas('translations', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // 2) Filter by category slug
            if ($category = $request->query('category')) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }

            // 3) Filter by city
            if ($city = $request->query('city')) {
                $query->whereHas('cities', function ($q) use ($city) {
                    $q->where('slug', $city);
                });
            }

            // 4) Sorting - whitelist filtered fields and directions
            $allowedSorts = ['rank', 'discount_rate', 'created_at', 'updated_at'];
            if (!in_array($sortField, $allowedSorts)) {
                $sortField = 'rank';
            }
            $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortDir);

            // 5) Pagination and access via Resources
            $restaurants = $query->paginate($perPage);

            return RestaurantShortResource::collection($restaurants)
                ->additional([
                    'meta' => [
                        'per_page' => $restaurants->perPage(),
                        'current_page' => $restaurants->currentPage(),
                        'last_page' => $restaurants->lastPage(),
                        'total' => $restaurants->total(),
                    ],
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch restaurants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get restaurant by slug
     */
    public function showBySlug(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->firstOrFail();

            return RestaurantResource::make($restaurant);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Restaurant', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant details by slug with related data
     */
    public function showDetails(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with(['places.tables', 'tables'])
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantResource::make($restaurant),
                    'places' => $restaurant->places ?? [],
                    'tables' => $restaurant->tables ?? [],
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Restaurant', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant with places
     */
    public function showByPlaces(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with('places')
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'places' => $restaurant->places ?? []
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Restaurant', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant by slug and place
     */
    public function showByPlace(string $slug, $placeIdentifier)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with('places')
                ->firstOrFail();

            // Find place by id or slug
            $place = $restaurant->places
                ->where('id', $placeIdentifier)
                ->first()
                ?? $restaurant->places
                ->where('slug', $placeIdentifier)
                ->first();

            if (!$place) {
                return response()->json(['error' => 'Place not found'], 404);
            }

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'place' => $place
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch place', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant tables in a specific place by slug and place slug
     */
    public function showTablesInPlace(string $slug, string $place)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with(['places.tables'])
                ->firstOrFail();

            $placeModel = $restaurant->places->where('slug', $place)->first();
            if (!$placeModel) {
                return response()->json(['error' => 'Place not found'], 404);
            }

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'place' => $placeModel,
                    'tables' => $placeModel->tables ?? []
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch tables', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant table in a specific place
     */
    public function showTableInPlace(string $slug, string $place, string $table)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with(['places.tables'])
                ->firstOrFail();

            $placeModel = $restaurant->places->where('slug', $place)->first();
            if (!$placeModel) {
                return response()->json(['error' => 'Place not found'], 404);
            }

            $tableModel = $placeModel->tables->where('id', $table)->first()
                ?? $placeModel->tables->where('slug', $table)->first();

            if (!$tableModel) {
                return response()->json(['error' => 'Table not found'], 404);
            }

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'place' => $placeModel,
                    'table' => $tableModel
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch table', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant details by slug with tables
     */
    public function showByTables(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with('tables')
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'tables' => $restaurant->tables ?? []
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch Restaurant', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant table details by slug and table id
     */
    public function showTable(string $slug, string $table)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->with(['tables' => function ($q) use ($table) {
                    $q->where('id', $table)->orWhere('slug', $table);
                }])
                ->firstOrFail();

            $tableModel = $restaurant->tables->first();

            if (!$tableModel) {
                return response()->json(['error' => 'Table not found'], 404);
            }

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'table' => $tableModel
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch table', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all menu categories related to a restaurant by slug
     */
    public function menuCategories(Request $request, $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'menu_categories' => [] // Will be populated when MenuCategory model is ready
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menu categories', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all menu items related to a restaurant by slug
     */
    public function menuItems(Request $request, $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'menu_items' => [] // Will be populated when MenuItem model is ready
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menu items', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get restaurant info with menu: parent categories, child categories, and items per category
     */
    public function showMenu(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'menu' => [] // Will be populated when MenuCategory and MenuItem models are ready
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menu tree', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get full menu with categories and items for a restaurant by slug
     */
    public function showFullMenu(string $slug)
    {
        try {
            $restaurant = Restaurant::where('slug', $slug)
                ->where('status', Restaurant::STATUS_ACTIVE)
                ->firstOrFail();

            return response()->json([
                'data' => [
                    'restaurant' => RestaurantShortResource::make($restaurant),
                    'menu' => [] // Will be populated when MenuCategory and MenuItem models are ready
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menu tree', 'message' => $e->getMessage()], 500);
        }
    }
}