<?php

namespace App\Traits;

use App\Activity;

trait RecordActivity
{
    public $oldAttributes = [];

    public static function bootRecordActivity()
    {
        static::updating(function ($model) {
            $model->oldAttributes = $model->getOriginal();
        });

        foreach (static::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });
        }
    }

    public static function recordableEvents()
    {
        return isset(static::$recordableEvents) ? static::$recordableEvents : ['created', 'updated', 'deleted'];
    }

    public function activityDescription($description)
    {
        return strtolower(class_basename($this)).'_'.$description;
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject')->latest('updated_at');
    }

    public function recordActivity($description)
    {
        $this->activities()->create([
            'description' => $description,
            'project_id' => $this->getProjectId(),
            'changes' =>  $this->activityChanges()
        ]);
    }

    public function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_diff($this->oldAttributes, $this->getAttributes()),
                // 'after' => array_diff($this->getAttributes(), $this->oldAttributes)
                'after' => $this->getChanges()
            ];
        }
    }

    public function getProjectId()
    {
        return class_basename($this) === 'Project' ? $this->id : $this->project->id;
    }
}
