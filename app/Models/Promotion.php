<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Promotion extends Model
{
    use HasFactory , HasApiTokens;
    protected $fillable = [
        'promotion_type',
        'duration',
        'user_id',
        'is_active'
    ];

    protected $casts = [
        'duration' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }


}
