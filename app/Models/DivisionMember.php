<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DivisionMember extends Model
{
    use HasFactory;

    protected $fillable = ['division_id', 'name', 'role', 'order'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
