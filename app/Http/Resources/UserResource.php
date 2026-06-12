<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'phone' => $this->phone,
            'avatar_url' => $this->avatar_url,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'last_login_at' => $this->last_login_at,
            'roles' => $this->roles->pluck('slug'),
            'permissions' => $this->permissions->pluck('slug'),
            'has_mfa' => $this->hasMfaEnabled(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
