<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    const STATUS = [
        'Submitted' => 'Submitted',
        'Revision' => 'Revision',
        'Approve by Leader' => 'Approve by Leader',
        'In Progress' => 'In Progress',
        'Completed' => 'Completed',
    ];

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'assigned_leader',
        'status',
        'progress',
        'progress_by',
        'deadline',
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'assigned_leader');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function progressBy()
    {
        return $this->belongsTo(User::class, 'progress_by');
    }
}
