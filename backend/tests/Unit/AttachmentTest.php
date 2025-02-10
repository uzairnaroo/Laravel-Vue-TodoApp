<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Attachment;
use App\Models\Task;

class AttachmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_task()
    {
        $task = Task::factory()->create();
        $attachment = Attachment::factory()->create(['task_id' => $task->id]);

        $this->assertInstanceOf(Task::class, $attachment->task);
        $this->assertEquals($task->id, $attachment->task->id);
    }
}
