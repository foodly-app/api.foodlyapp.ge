<?php

namespace App\Http\Resources\Place;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceResource extends JsonResource
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
                'is_active' => $this->is_active,
                'rank' => $this->rank,
                'slug' => $this->slug,
                'name' => $this->name,
                'description' => $this->description,
                'image' => $this->image,
                'image_link' => $this->image_link,
                'full_image_url' => $this->full_image_url,
                'qr_code' => $this->qr_code,
                'qr_code_image' => $this->qr_code_image,
                'qr_code_link' => $this->qr_code_link,
                'full_qr_code_url' => $this->full_qr_code_url,
                'restaurant_id' => $this->restaurant_id,
                'restaurant' => $this->whenLoaded('restaurant', function () {
                    return [
                        'id' => $this->restaurant->id,
                        'name' => $this->restaurant->name,
                        'slug' => $this->restaurant->slug,
                    ];
                }),
                // 'tables' => TableResource::collection($this->whenLoaded('tables')),
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
            ];
        }

        // Return all translations when no specific locale is requested
        return [
            'id' => $this->id,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'slug' => $this->slug,
            'rank' => $this->rank,
            'image' => $this->image,
            'image_link' => $this->image_link,
            'full_image_url' => $this->full_image_url,
            'qr_code' => $this->qr_code,
            'qr_code_image' => $this->qr_code_image,
            'qr_code_link' => $this->qr_code_link,
            'full_qr_code_url' => $this->full_qr_code_url,
            'restaurant_id' => $this->restaurant_id,
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
            'translations' => $this->translations->map(function ($translation) {
                return [
                    'locale' => $translation->locale,
                    'name' => $translation->name,
                    'description' => $translation->description,
                ];
            }),
            // 'tables' => TableResource::collection($this->whenLoaded('tables')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}