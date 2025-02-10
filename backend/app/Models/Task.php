<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Task extends Model
/**
 * Represents a task in the application.
 *
 * The Task model has various attributes such as user_id, title, description, priority, due_date, completion status, and archival status. It also has relationships with the User and Attachment models.
 */
{
    use HasFactory, HasTags;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'due_date',
        'is_completed',
        'completed_at',
        'is_archived',
        'archived_at',
    ];

    protected $dates = [
        'completed_at',
        'archived_at',
        'due_date',
        'created_at',
        'updated_at',
    ];


    protected $casts = [
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
