<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectorPassword extends Model
{
    protected $fillable = [
        'sector_number',
        'sector_name',
        'uuid_password',
    ];
}
