<?php

namespace App\Http\Resources\MenuItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $locale = $request->query('locale');
        
        if ($locale) {
            app()->setLocale($locale);
            return [
                'id'                => $this->id,
                'restaurant_id'     => $this->restaurant_id,
                'menu_category_id'  => $this->menu_category_id,
                'name'              => $this->name,
                'description'       => $this->description,
                'ingredients'       => $this->ingredients,
                'allergens'         => $this->allergens,
                'slug'              => $this->slug,
                'price'             => $this->price,
                'discounted_price'  => $this->discounted_price,
                'effective_price'   => $this->effective_price,
                'has_discount'      => $this->has_discount,
                'discount_percentage' => $this->discount_percentage,
                'unit'              => $this->unit,
                'unit_label'        => $this->unit_label,
                'quantity'          => $this->quantity,
                'image'             => $this->image,
                'image_link'        => $this->image_link,
                'rank'              => $this->rank,
                'status'            => $this->status,
                'status_label'      => $this->status_label,
                'available'         => $this->available,
                'is_vegan'          => $this->is_vegan,
                'is_gluten_free'    => $this->is_gluten_free,
                'calories'          => $this->calories,
                'preparation_time'  => $this->preparation_time,
                'created_at'        => $this->created_at,
                'updated_at'        => $this->updated_at,
            ];
        }

        return [
            'id'                => $this->id,
            'restaurant_id'     => $this->restaurant_id,
            'menu_category_id'  => $this->menu_category_id,
            'slug'              => $this->slug,
            'price'             => $this->price,
            'discounted_price'  => $this->discounted_price,
            'effective_price'   => $this->effective_price,
            'has_discount'      => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'unit'              => $this->unit,
            'unit_label'        => $this->unit_label,
            'quantity'          => $this->quantity,
            'image'             => $this->image,
            'image_link'        => $this->image_link,
            'rank'              => $this->rank,
            'status'            => $this->status,
            'status_label'      => $this->status_label,
            'available'         => $this->available,
            'is_vegan'          => $this->is_vegan,
            'is_gluten_free'    => $this->is_gluten_free,
            'calories'          => $this->calories,
            'preparation_time'  => $this->preparation_time,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'translations'      => $this->translations->map(function ($tr) {
                return [
                    'locale'      => $tr->locale,
                    'name'        => $tr->name,
                    'description' => $tr->description,
                    'ingredients' => $tr->ingredients,
                    'allergens'   => $tr->allergens,
                ];
            }),
        ];
    }
}