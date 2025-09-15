<?php

namespace App\Http\Resources\MenuCategory;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Restaurant\RestaurantShortResource;
use App\Http\Resources\MenuCategory\MenuCategoryResource;

class RestaurantCategoryItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'restaurant' => new RestaurantShortResource($this->resource),
            'category'   => new MenuCategoryResource($this->whenLoaded('category')),
            // 'products' will be available when MenuItem model is created
            // 'products'   => MenuItemResource::collection($this->whenLoaded('menuItems')),
        ];
    }
}