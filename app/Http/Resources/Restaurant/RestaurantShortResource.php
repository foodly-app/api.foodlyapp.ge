<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantShortResource extends JsonResource
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
                'rank' => $this->rank,
                'slug' => $this->slug,
                'status' => $this->status,
                'name' => $this->name,
                'description' => $this->description,
                'address' => $this->address,
                'logo' => $this->logo,
                'image' => $this->image,
                'discount_rate' => $this->discount_rate,
                'price_per_person' => $this->price_per_person,
                'working_hours' => $this->working_hours,
                'qr_code_image' => $this->qr_code_image,
                'reservation_type' => $this->reservation_type,
            ];
        }

        return [
            'id' => $this->id,
            'rank' => $this->rank,
            'slug' => $this->slug,
            'status' => $this->status,
            'logo' => $this->logo,
            'image' => $this->image,
            'discount_rate' => $this->discount_rate,
            'price_per_person' => $this->price_per_person,
            'qr_code_image' => $this->qr_code_image,
            'reservation_type' => $this->reservation_type,

            'translations' => $this->translations->map(function ($tr) {
                return [
                    'locale' => $tr->locale,
                    'name' => $tr->name,
                    'description' => $tr->description,
                    'address' => $tr->address,
                ];
            }),
        ];
    }
}
