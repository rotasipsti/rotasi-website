<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'desc', 'order'];

    public function members()
    {
        return $this->hasMany(DivisionMember::class)->orderBy('order');
    }
}
