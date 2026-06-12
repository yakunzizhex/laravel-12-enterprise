<?php

namespace App\Traits;

use Activity;

trait HasAuditLog
{
    /**
     * Log activity
     */
    public static function bootHasAuditLog()
    {
        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->withProperties(['attributes' => $model->getAttributes()])
                ->log('created');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->withProperties(['changes' => $model->getChanges()])
                ->log('updated');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->withProperties(['attributes' => $model->getAttributes()])
                ->log('deleted');
        });
    }

    /**
     * Get audit logs
     */
    public function activities()
    {
        return Activity::all()
            ->where('subject_type', get_class($this))
            ->where('subject_id', $this->getKey());
    }
}
