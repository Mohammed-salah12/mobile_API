<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Contact extends Model
{
    use HasFactory , HasApiTokens;

    protected $fillable = [
        'name',
        'number',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
