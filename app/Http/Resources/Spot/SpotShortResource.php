<?php

namespace App\Http\Resources\Spot;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpotShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
