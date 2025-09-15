<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Table\Table;
use App\Http\Resources\Table\TableResource;
use App\Http\Resources\Table\TableShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TableController extends Controller
{
    /**
     * Display a listing of tables
     */
    public function index(Request $request): JsonResponse
    {
        $query = Table::with(['restaurant', 'place', 'translations']);

        // Filter by restaurant
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        // Filter by place
        if ($request->has('place_id')) {
            $query->byPlace($request->place_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to available tables
            $query->available();
        }

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->byCapacity($request->min_capacity);
        }
        if ($request->has('max_capacity')) {
            $query->byCapacity(null, $request->max_capacity);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'capacity_asc':
                $query->orderBy('capacity', 'asc');
                break;
            case 'capacity_desc':
                $query->orderBy('capacity', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $tables = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => TableShortResource::collection($tables->items()),
            'meta' => [
                'current_page' => $tables->currentPage(),
                'last_page' => $tables->lastPage(),
                'per_page' => $tables->perPage(),
                'total' => $tables->total(),
                'from' => $tables->firstItem(),
                'to' => $tables->lastItem(),
            ],
        ]);
    }

    /**
     * Display the specified table by ID
     */
    public function show(int $id): JsonResponse
    {
        $table = Table::with(['restaurant', 'place', 'translations'])
            ->available()
            ->findOrFail($id);

        return response()->json([
            'data' => new TableResource($table),
        ]);
    }

    /**
     * Display the specified table by slug
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $table = Table::with(['restaurant', 'place', 'translations'])
            ->available()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => new TableResource($table),
        ]);
    }

    /**
     * Get tables by restaurant
     */
    public function getByRestaurant(Request $request, int $restaurantId): JsonResponse
    {
        $query = Table::with(['restaurant', 'place', 'translations'])
            ->byRestaurant($restaurantId)
            ->available();

        // Filter by place if provided
        if ($request->has('place_id')) {
            $query->byPlace($request->place_id);
        }

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->byCapacity($request->min_capacity);
        }
        if ($request->has('max_capacity')) {
            $query->byCapacity(null, $request->max_capacity);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'capacity_asc':
                $query->orderBy('capacity', 'asc');
                break;
            case 'capacity_desc':
                $query->orderBy('capacity', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $tables = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => TableShortResource::collection($tables->items()),
            'meta' => [
                'current_page' => $tables->currentPage(),
                'last_page' => $tables->lastPage(),
                'per_page' => $tables->perPage(),
                'total' => $tables->total(),
                'from' => $tables->firstItem(),
                'to' => $tables->lastItem(),
            ],
        ]);
    }

    /**
     * Get tables by place
     */
    public function getByPlace(Request $request, int $placeId): JsonResponse
    {
        $query = Table::with(['restaurant', 'place', 'translations'])
            ->byPlace($placeId)
            ->available();

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->byCapacity($request->min_capacity);
        }
        if ($request->has('max_capacity')) {
            $query->byCapacity(null, $request->max_capacity);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'capacity_asc':
                $query->orderBy('capacity', 'asc');
                break;
            case 'capacity_desc':
                $query->orderBy('capacity', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $tables = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => TableShortResource::collection($tables->items()),
            'meta' => [
                'current_page' => $tables->currentPage(),
                'last_page' => $tables->lastPage(),
                'per_page' => $tables->perPage(),
                'total' => $tables->total(),
                'from' => $tables->firstItem(),
                'to' => $tables->lastItem(),
            ],
        ]);
    }

    /**
     * Get available tables
     */
    public function getAvailable(Request $request): JsonResponse
    {
        $query = Table::with(['restaurant', 'place', 'translations'])
            ->available();

        // Filter by restaurant if provided
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        // Filter by place if provided
        if ($request->has('place_id')) {
            $query->byPlace($request->place_id);
        }

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->byCapacity($request->min_capacity);
        }
        if ($request->has('max_capacity')) {
            $query->byCapacity(null, $request->max_capacity);
        }

        $limit = $request->get('limit', 10);
        $tables = $query->orderByRank('asc')->limit($limit)->get();

        return response()->json([
            'data' => TableShortResource::collection($tables),
        ]);
    }

    /**
     * Search tables
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->get('search', '');
        
        if (empty($searchTerm)) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'total' => 0,
                    'from' => null,
                    'to' => null,
                ],
            ]);
        }

        $query = Table::with(['restaurant', 'place', 'translations'])
            ->available()
            ->whereHas('translations', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });

        // Filter by restaurant if provided
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        // Filter by place if provided
        if ($request->has('place_id')) {
            $query->byPlace($request->place_id);
        }

        $tables = $query->orderByRank('asc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => TableShortResource::collection($tables->items()),
            'meta' => [
                'current_page' => $tables->currentPage(),
                'last_page' => $tables->lastPage(),
                'per_page' => $tables->perPage(),
                'total' => $tables->total(),
                'from' => $tables->firstItem(),
                'to' => $tables->lastItem(),
            ],
        ]);
    }

    /**
     * Get tables by restaurant slug
     */
    public function getByRestaurantSlug(Request $request, string $restaurantSlug): JsonResponse
    {
        $query = Table::with(['restaurant', 'place', 'translations'])
            ->whereHas('restaurant', function ($q) use ($restaurantSlug) {
                $q->where('slug', $restaurantSlug);
            })
            ->available();

        // Filter by place slug if provided
        if ($request->has('place_slug')) {
            $query->whereHas('place', function ($q) use ($request) {
                $q->where('slug', $request->place_slug);
            });
        }

        // Filter by capacity
        if ($request->has('min_capacity')) {
            $query->byCapacity($request->min_capacity);
        }
        if ($request->has('max_capacity')) {
            $query->byCapacity(null, $request->max_capacity);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'capacity_asc':
                $query->orderBy('capacity', 'asc');
                break;
            case 'capacity_desc':
                $query->orderBy('capacity', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $tables = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => TableShortResource::collection($tables->items()),
            'meta' => [
                'current_page' => $tables->currentPage(),
                'last_page' => $tables->lastPage(),
                'per_page' => $tables->perPage(),
                'total' => $tables->total(),
                'from' => $tables->firstItem(),
                'to' => $tables->lastItem(),
            ],
        ]);
    }
}