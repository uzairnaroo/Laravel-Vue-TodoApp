<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date instanceof \Carbon\Carbon
                ? $this->due_date->toDateTimeString()
                : $this->due_date,
            'is_completed' => $this->is_completed,
            'completed_at' => $this->completed_at instanceof \Carbon\Carbon
                ? $this->completed_at->toDateTimeString()
                : $this->completed_at,
            'is_archived' => $this->is_archived,
            'archived_at' => $this->archived_at instanceof \Carbon\Carbon
                ? $this->archived_at->toDateTimeString()
                : $this->archived_at,
            'tags' => $this->tags->pluck('name'),
            'attachments' => $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'url' => Storage::disk('public')->url($attachment->file_path),
                    'type' => $attachment->file_type,
                ];
            }),
            'created_at' => $this->created_at instanceof \Carbon\Carbon
                ? $this->created_at->toDateTimeString()
                : $this->created_at,
            'updated_at' => $this->updated_at instanceof \Carbon\Carbon
                ? $this->updated_at->toDateTimeString()
                : $this->updated_at,
        ];
    }

}
