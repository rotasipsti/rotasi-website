<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'sector',
        'due_date',
        'status',
        'task_type',
        'attachment_type',
        'attachment_url',
        'created_by',
        'is_draft',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_draft' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
