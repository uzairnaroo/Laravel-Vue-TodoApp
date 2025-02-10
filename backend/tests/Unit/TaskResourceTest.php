<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class TaskResourceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_transforms_task_model_into_an_array()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $attachments = Attachment::factory()->count(2)->create(['task_id' => $task->id]);

        $resource = new TaskResource($task->load('attachments', 'tags'));
        $taskArray = $resource->toArray(request());

        $this->assertEquals($task->id, $taskArray['id']);
        $this->assertEquals($task->title, $taskArray['title']);
        $this->assertEquals($task->description, $taskArray['description']);
        $this->assertEquals($task->priority, $taskArray['priority']);
        $this->assertEquals($task->due_date->toDateTimeString(), $taskArray['due_date']);
        $this->assertEquals($task->is_completed, $taskArray['is_completed']);
        $this->assertEquals($task->completed_at ? $task->completed_at->toDateTimeString() : null, $taskArray['completed_at']);
        $this->assertEquals($task->is_archived, $taskArray['is_archived']);
        $this->assertEquals($task->archived_at ? $task->archived_at->toDateTimeString() : null, $taskArray['archived_at']);
        $this->assertEquals($task->tags->pluck('name')->toArray(), $taskArray['tags']);

        foreach ($attachments as $attachment) {
            $this->assertContains([
                'id' => $attachment->id,
                'url' => Storage::disk('public')->url($attachment->file_path),
                'type' => $attachment->file_type,
            ], $taskArray['attachments']);
        }

        $this->assertEquals($task->created_at->toDateTimeString(), $taskArray['created_at']);
        $this->assertEquals($task->updated_at->toDateTimeString(), $taskArray['updated_at']);
    }
}
