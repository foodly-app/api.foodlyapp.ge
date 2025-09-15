<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Menu\MenuItem;
use App\Models\Menu\MenuCategory;
use App\Models\Restaurant\Restaurant;
use App\Http\Resources\MenuItem\MenuItemResource;
use App\Http\Resources\MenuItem\MenuItemShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuItemController extends Controller
{
    /**
     * Get all menu items with filtering and pagination
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
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

        // Filter by menu category
        if ($request->has('menu_category_id')) {
            $query->where('menu_category_id', $request->menu_category_id);
        }

        // Filter by category slug
        if ($request->has('category_slug')) {
            $category = MenuCategory::where('slug', $request->category_slug)->first();
            if ($category) {
                $query->where('menu_category_id', $category->id);
            }
        }

        // Filter by dietary preferences
        if ($request->boolean('vegan_only')) {
            $query->vegan();
        }

        if ($request->boolean('gluten_free_only')) {
            $query->glutenFree();
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter items with discount
        if ($request->boolean('discount_only')) {
            $query->withDiscount();
        }

        // Search in item name, description, and ingredients
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ingredients', 'like', "%{$search}%");
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
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderByTranslation('name', 'asc');
                break;
            case 'name_desc':
                $query->orderByTranslation('name', 'desc');
                break;
            case 'calories_asc':
                $query->orderBy('calories', 'asc');
                break;
            case 'calories_desc':
                $query->orderBy('calories', 'desc');
                break;
            case 'preparation_time_asc':
                $query->orderBy('preparation_time', 'asc');
                break;
            case 'preparation_time_desc':
                $query->orderBy('preparation_time', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $items = $query->paginate($perPage);

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get menu item by slug
     */
    public function showBySlug(string $slug, Request $request): MenuItemResource
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $item = MenuItem::with(['translations', 'restaurant', 'category'])
            ->where('slug', $slug)
            ->active()
            ->available()
            ->firstOrFail();

        return new MenuItemResource($item);
    }

    /**
     * Get menu item by ID
     */
    public function show(int $id, Request $request): MenuItemResource
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $item = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
            ->findOrFail($id);

        return new MenuItemResource($item);
    }

    /**
     * Get menu items by restaurant slug
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

        $query = MenuItem::with(['translations', 'category'])
            ->where('restaurant_id', $restaurant->id)
            ->active()
            ->available()
            ->ordered();

        // Filter by category if provided
        if ($request->has('category_slug')) {
            $category = MenuCategory::where('slug', $request->category_slug)
                ->where('restaurant_id', $restaurant->id)
                ->first();
            if ($category) {
                $query->where('menu_category_id', $category->id);
            }
        }

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get menu items by category
     */
    public function getByCategory(string $categorySlug, Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $category = MenuCategory::where('slug', $categorySlug)
            ->active()
            ->firstOrFail();

        $query = MenuItem::with(['translations', 'restaurant'])
            ->where('menu_category_id', $category->id)
            ->active()
            ->available()
            ->ordered();

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get featured/popular menu items
     */
    public function getFeatured(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
            ->orderBy('rank', 'asc')
            ->limit(10);

        // Filter by restaurant if provided
        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get menu items with discount
     */
    public function getDiscounted(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
            ->withDiscount()
            ->orderBy('rank', 'asc');

        // Filter by restaurant if provided
        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get vegan menu items
     */
    public function getVegan(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
            ->vegan()
            ->ordered();

        // Filter by restaurant if provided
        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }

    /**
     * Get gluten-free menu items
     */
    public function getGlutenFree(Request $request): AnonymousResourceCollection
    {
        $locale = $request->query('locale');
        if ($locale) {
            app()->setLocale($locale);
        }

        $query = MenuItem::with(['translations', 'restaurant', 'category'])
            ->active()
            ->available()
            ->glutenFree()
            ->ordered();

        // Filter by restaurant if provided
        if ($request->has('restaurant_slug')) {
            $restaurant = Restaurant::where('slug', $request->restaurant_slug)->first();
            if ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            }
        }

        $items = $query->get();

        return MenuItemShortResource::collection($items);
    }
}