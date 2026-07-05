<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'date', 'location', 'duration', 'desc', 'activities', 'order'
    ];

    protected $casts = [
        'activities' => 'array',
    ];
}
