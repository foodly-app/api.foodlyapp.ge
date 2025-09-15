<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Place\Place;
use App\Http\Resources\Place\PlaceResource;
use App\Http\Resources\Place\PlaceShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlaceController extends Controller
{
    /**
     * Display a listing of places
     */
    public function index(Request $request): JsonResponse
    {
        $query = Place::with(['restaurant', 'translations']);

        // Filter by restaurant
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to active places
            $query->active();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
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

        $places = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PlaceShortResource::collection($places->items()),
            'meta' => [
                'current_page' => $places->currentPage(),
                'last_page' => $places->lastPage(),
                'per_page' => $places->perPage(),
                'total' => $places->total(),
                'from' => $places->firstItem(),
                'to' => $places->lastItem(),
            ],
        ]);
    }

    /**
     * Display the specified place by ID
     */
    public function show(int $id): JsonResponse
    {
        $place = Place::with(['restaurant', 'translations'])
            ->active()
            ->findOrFail($id);

        return response()->json([
            'data' => new PlaceResource($place),
        ]);
    }

    /**
     * Display the specified place by slug
     */
    public function showBySlug(string $slug): JsonResponse
    {
        $place = Place::with(['restaurant', 'translations'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => new PlaceResource($place),
        ]);
    }

    /**
     * Get places by restaurant
     */
    public function getByRestaurant(Request $request, int $restaurantId): JsonResponse
    {
        $query = Place::with(['restaurant', 'translations'])
            ->byRestaurant($restaurantId)
            ->active();

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $places = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PlaceShortResource::collection($places->items()),
            'meta' => [
                'current_page' => $places->currentPage(),
                'last_page' => $places->lastPage(),
                'per_page' => $places->perPage(),
                'total' => $places->total(),
                'from' => $places->firstItem(),
                'to' => $places->lastItem(),
            ],
        ]);
    }

    /**
     * Get featured places
     */
    public function getFeatured(Request $request): JsonResponse
    {
        $query = Place::with(['restaurant', 'translations'])
            ->active()
            ->orderByRank('asc');

        // Filter by restaurant if provided
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        $limit = $request->get('limit', 10);
        $places = $query->limit($limit)->get();

        return response()->json([
            'data' => PlaceShortResource::collection($places),
        ]);
    }

    /**
     * Get places by restaurant slug
     */
    public function getByRestaurantSlug(Request $request, string $restaurantSlug): JsonResponse
    {
        $query = Place::with(['restaurant', 'translations'])
            ->whereHas('restaurant', function ($q) use ($restaurantSlug) {
                $q->where('slug', $restaurantSlug);
            })
            ->active();

        // Sorting
        $sortBy = $request->get('sort_by', 'rank_asc');
        switch ($sortBy) {
            case 'rank_desc':
                $query->orderByRank('desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderByRank('asc');
                break;
        }

        $places = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PlaceShortResource::collection($places->items()),
            'meta' => [
                'current_page' => $places->currentPage(),
                'last_page' => $places->lastPage(),
                'per_page' => $places->perPage(),
                'total' => $places->total(),
                'from' => $places->firstItem(),
                'to' => $places->lastItem(),
            ],
        ]);
    }

    /**
     * Search places
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

        $query = Place::with(['restaurant', 'translations'])
            ->active()
            ->whereHas('translations', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });

        // Filter by restaurant if provided
        if ($request->has('restaurant_id')) {
            $query->byRestaurant($request->restaurant_id);
        }

        $places = $query->orderByRank('asc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => PlaceShortResource::collection($places->items()),
            'meta' => [
                'current_page' => $places->currentPage(),
                'last_page' => $places->lastPage(),
                'per_page' => $places->perPage(),
                'total' => $places->total(),
                'from' => $places->firstItem(),
                'to' => $places->lastItem(),
            ],
        ]);
    }
}