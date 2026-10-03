<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'target_role',
        'link',
        'link_text',
        'is_active',
    ];
}
