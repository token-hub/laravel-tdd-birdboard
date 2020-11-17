<?php

namespace App;

use App\User;
use App\Traits\Tasksable;
use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Tasksable, RecordActivity;

    protected $guarded = [];

    public static $recordableEvents = ['created', 'updated'];

    public function path()
    {
        return "projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest('updated_at');
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function isMember(User $user)
    {
        return $this->members->contains($user);
    }
}
