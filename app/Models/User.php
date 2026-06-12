<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\MultiFactorAuthenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, MultiFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar_url',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Many roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Relationship: Many permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    /**
     * Relationship: Audit logs
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user has role
     */
    public function hasRole(string $slug): bool
    {
        return $this->roles()
            ->where('slug', $slug)
            ->exists();
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(string $slug): bool
    {
        // Check direct permissions
        if ($this->permissions()->where('slug', $slug)->exists()) {
            return true;
        }

        // Check permissions through roles
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->exists();
    }

    /**
     * Grant role
     */
    public function grantRole(string $slug): void
    {
        $role = Role::where('slug', $slug)->first();
        if ($role && !$this->hasRole($slug)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Revoke role
     */
    public function revokeRole(string $slug): void
    {
        $role = Role::where('slug', $slug)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Assign permission directly
     */
    public function assignPermission(string $slug): void
    {
        $permission = Permission::where('slug', $slug)->first();
        if ($permission && !$this->hasPermission($slug)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Check if account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if account is active
     */
    public function isActive(): bool
    {
        return $this->is_active && !$this->isLocked();
    }

    /**
     * Scope: Active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('locked_until')
                    ->orWhere('locked_until', '<', now());
            });
    }

    /**
     * Scope: By role
     */
    public function scopeByRole($query, string $slug)
    {
        return $query->whereHas('roles', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }
}
