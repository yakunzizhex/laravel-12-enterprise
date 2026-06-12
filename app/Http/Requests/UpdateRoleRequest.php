<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->hasPermission('roles.edit');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $this->route('role'),
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,slug',
        ];
    }
}
