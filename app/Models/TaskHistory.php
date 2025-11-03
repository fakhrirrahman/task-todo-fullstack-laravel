<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;

    protected $table = 'task_history';

    const ACTIONS = [
        'submit' => 'submit',
        'revision' => 'revision',
        'approve' => 'approve',
        'update progress' => 'update progress',
        'complete' => 'complete'
    ];

    protected $fillable = [
        'task_id',
        'action_by',
        'action',
        'note',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
