<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantDetailsResource extends JsonResource
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
                'slug' => $this->slug,
                'status' => $this->status,
                'name' => $this->name,
                'description' => $this->description,
                'address' => $this->address,
                'logo' => $this->logo,
                'image' => $this->image,
                'video' => $this->video,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'website' => $this->website,
                'discount_rate' => $this->discount_rate,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'working_hours' => $this->working_hours,
                'map_link' => $this->map_link,
                'delivery_time' => $this->delivery_time,
                'map_embed_link' => $this->map_embed_link,
                'price_per_person' => $this->price_per_person,
                'price_currency' => $this->price_currency,
                'time_zone' => $this->time_zone,
                'reservation_type' => $this->reservation_type,
                'qr_code_image' => $this->qr_code_image,
                'qr_code_link' => $this->qr_code_link,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'status' => $this->status,
            'logo' => $this->logo,
            'image' => $this->image,
            'video' => $this->video,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,
            'discount_rate' => $this->discount_rate,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'working_hours' => $this->working_hours,
            'map_link' => $this->map_link,
            'delivery_time' => $this->delivery_time,
            'map_embed_link' => $this->map_embed_link,
            'price_per_person' => $this->price_per_person,
            'price_currency' => $this->price_currency,
            'time_zone' => $this->time_zone,
            'reservation_type' => $this->reservation_type,
            'qr_code_image' => $this->qr_code_image,
            'qr_code_link' => $this->qr_code_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

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