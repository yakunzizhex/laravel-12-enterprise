<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Many users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Relationship: Many permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Sync permissions
     */
    public function syncPermissions(array $permissions): void
    {
        $permissionIds = Permission::whereIn('slug', $permissions)->pluck('id')->toArray();
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Grant permission
     */
    public function grantPermission(string $slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission && !$this->hasPermission($slug)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Revoke permission
     */
    public function revokePermission(string $slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Check if role has permission
     */
    public function hasPermission(string $slug): bool
    {
        return $this->permissions()
            ->where('slug', $slug)
            ->exists();
    }

    /**
     * Scope: Active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
