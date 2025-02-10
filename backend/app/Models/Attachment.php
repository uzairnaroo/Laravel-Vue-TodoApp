<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
/**
 * Represents an attachment associated with a task.
 *
 * The Attachment model has the following fillable attributes:
 * - `task_id`: the ID of the task the attachment is associated with
 * - `file_path`: the path to the file on the server
 * - `file_type`: the type of the file
 *
 * The Attachment model also has a relationship with the Task model, where an attachment belongs to a task.
 */
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_path',
        'file_type',
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
