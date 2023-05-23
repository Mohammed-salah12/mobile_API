<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Project extends Model
{
    use HasFactory , HasApiTokens;

    protected $fillable = [
        'name',
        'type_Of_Project',
        'category',
        'priority',
        'status',
        'time_Hours',
        'time_Min',
        'time_Am_BM',
        'is_active',
    ];



    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'projects_has_users')
            ->withTimestamps()
            ->withPivot('is_active');
    }
}
