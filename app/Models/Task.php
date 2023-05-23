<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Task extends Model
{
    use HasFactory , HasApiTokens;

    protected $fillable = [
        'name',
        'category_id',
        'status',
        'description',
        'time_Min',
        'time_Am_BM',
        'sort',
        'project_id',
        'is_active',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function featuredByUsers()
    {
        return $this->hasMany(Featured_Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tasks_has_users')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Project::class, 'category_id');
    }


}
