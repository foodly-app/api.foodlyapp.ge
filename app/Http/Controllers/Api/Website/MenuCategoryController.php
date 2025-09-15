<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Menu\MenuCategory;
use App\Models\Restaurant\Restaurant;
use App\Http\Resources\MenuCategory\MenuCategoryResource;
use App\Http\Resources\MenuCategory\RestaurantCategoryItemsResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuCategoryController extends Controller
{
    /**
     * Get all menu categories with filtering and pagination
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuCategory::with(['translations', 'restaurant', 'children'])
            ->active()
            ->ordered();

        // Filter by restaurant
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by restaurant slug
        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        // Filter by parent category
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Only root categories (no parent)
        if ($request->boolean('root_only')) {
            $query->whereNull('parent_id');
        }

        // Search in category name and description
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'rank_asc');
        switch ($sortBy) {
            case 'rank_asc':
                $query->orderBy('rank', 'asc');
                break;
            case 'rank_desc':
                $query->orderBy('rank', 'desc');
                break;
            case 'name_asc':
                $query->orderByTranslation('name', 'asc');
                break;
            case 'name_desc':
                $query->orderByTranslation('name', 'desc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $categories = $query->paginate($perPage);

        return MenuCategoryResource::collection($categories);
    }

    /**
     * Get menu category by slug
     */
    public function showBySlug(string $slug, Request $request): MenuCategoryResource
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $category = MenuCategory::with(['translations', 'restaurant', 'children', 'parent'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return new MenuCategoryResource($category);
    }

    /**
     * Get menu category by ID
     */
    public function show(int $id, Request $request): MenuCategoryResource
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $category = MenuCategory::with(['translations', 'restaurant', 'children', 'parent'])
            ->active()
            ->findOrFail($id);

        return new MenuCategoryResource($category);
    }

    /**
     * Get menu categories by restaurant slug
     */
    public function getByRestaurant(string $restaurantSlug, Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $restaurant = Restaurant::where('slug', $restaurantSlug)
            ->active()
            ->firstOrFail();

        $query = MenuCategory::with(['translations', 'children'])
            ->where('restaurant_id', $restaurant->id)
            ->active()
            ->ordered();

        // Only root categories (no parent)
        if ($request->boolean('root_only')) {
            $query->whereNull('parent_id');
        }

        $categories = $query->get();

        return MenuCategoryResource::collection($categories);
    }

    /**
     * Get menu category hierarchy (tree structure)
     */
    public function getHierarchy(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuCategory::with(['translations', 'allChildren.translations'])
            ->whereNull('parent_id') // Only root categories
            ->active()
            ->ordered();

        // Filter by restaurant
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        $categories = $query->get();

        return MenuCategoryResource::collection($categories);
    }

    /**
     * Get category children
     */
    public function getChildren(int $parentId, Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $parent = MenuCategory::active()->findOrFail($parentId);

        $children = MenuCategory::with(['translations', 'children'])
            ->where('parent_id', $parent->id)
            ->active()
            ->ordered()
            ->get();

        return MenuCategoryResource::collection($children);
    }

    /**
     * Get breadcrumb path for a category
     */
    public function getBreadcrumb(int $id, Request $request): JsonResponse
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $category = MenuCategory::with(['translations'])
            ->active()
            ->findOrFail($id);

        $breadcrumbPath = $category->getBreadcrumbPath();

        $breadcrumb = collect($breadcrumbPath)->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            ];
        });

        return response()->json([
            'breadcrumb' => $breadcrumb,
            'depth' => $category->getDepthLevel(),
        ]);
    }

    /**
     * Get restaurant with its categories and items
     */
    public function getRestaurantCategoryItems(string $restaurantSlug, Request $request): RestaurantCategoryItemsResource
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $restaurant = Restaurant::with([
            'translations',
            // When MenuItem model is created, uncomment this:
            // 'menuItems' => function ($query) {
            //     $query->active()->ordered();
            // }
        ])
        ->where('slug', $restaurantSlug)
        ->active()
        ->firstOrFail();

        return new RestaurantCategoryItemsResource($restaurant);
    }
}