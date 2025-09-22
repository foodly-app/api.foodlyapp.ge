<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            // 'avatar' => $this->when($this->avatar, $this->avatar),
            // 'avatar_url' => $this->when($this->avatar, Storage::url($this->avatar)),
            'created_at' => $this->created_at,
            'updated_at' => $this->when($request->routeIs('website.users.profile'), $this->updated_at),
            'email_verified_at' => $this->when($request->routeIs('website.users.profile'), $this->email_verified_at),
        ];
    }
}