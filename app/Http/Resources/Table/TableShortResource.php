<?php

namespace App\Http\Resources\Table;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = $request->query('locale');
        
        if ($locale) {
            app()->setLocale($locale);
            return [
                'id' => $this->id,
                'status' => $this->status,
                'status_label' => $this->status_label,
                'is_active' => $this->is_active,
                'is_available' => $this->is_available,
                'is_reserved' => $this->is_reserved,
                'rank' => $this->rank,
                'slug' => $this->slug,
                'name' => $this->name,
                'description' => $this->description,
                'seats' => $this->seats,
                'capacity' => $this->capacity,
                'icon' => $this->icon,
                'image' => $this->image,
                'image_link' => $this->image_link,
                'full_image_url' => $this->full_image_url,
                'restaurant_id' => $this->restaurant_id,
                'place_id' => $this->place_id,
                'restaurant' => $this->whenLoaded('restaurant', function () {
                    return [
                        'id' => $this->restaurant->id,
                        'name' => $this->restaurant->name,
                        'slug' => $this->restaurant->slug,
                    ];
                }),
                'place' => $this->whenLoaded('place', function () {
                    return [
                        'id' => $this->place->id,
                        'name' => $this->place->name,
                        'slug' => $this->place->slug,
                    ];
                }),
            ];
        }

        // Return all translations when no specific locale is requested
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'is_active' => $this->is_active,
            'is_available' => $this->is_available,
            'is_reserved' => $this->is_reserved,
            'slug' => $this->slug,
            'rank' => $this->rank,
            'seats' => $this->seats,
            'capacity' => $this->capacity,
            'icon' => $this->icon,
            'image' => $this->image,
            'image_link' => $this->image_link,
            'full_image_url' => $this->full_image_url,
            'restaurant_id' => $this->restaurant_id,
            'place_id' => $this->place_id,
            'restaurant' => $this->whenLoaded('restaurant', function () {
                return [
                    'id' => $this->restaurant->id,
                    'slug' => $this->restaurant->slug,
                    'translations' => $this->restaurant->translations->map(function ($translation) {
                        return [
                            'locale' => $translation->locale,
                            'name' => $translation->name,
                        ];
                    }),
                ];
            }),
            'place' => $this->whenLoaded('place', function () {
                return [
                    'id' => $this->place->id,
                    'slug' => $this->place->slug,
                    'translations' => $this->place->translations->map(function ($translation) {
                        return [
                            'locale' => $translation->locale,
                            'name' => $translation->name,
                        ];
                    }),
                ];
            }),
            'translations' => $this->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }),
        ];
    }
}