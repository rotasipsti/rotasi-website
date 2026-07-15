<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'type',
        'image_path',
        'image_url',
        'action_url',
        'target_role',
        'is_active',
    ];
}
