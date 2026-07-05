<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'participant_id',
        'submission_text',
        'file_url',
        'file_name',
        'submitted_at',
        'status',
        'evaluation_score',
        'evaluation_comment',
        'evaluated_by',
        'evaluated_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'evaluated_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function participant()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
