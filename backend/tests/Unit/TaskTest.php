<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\User;
use App\Models\Attachment;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($user->id, $task->user->id);
    }

    #[Test]
    public function it_has_many_attachments()
    {
        $task = Task::factory()->create();
        Attachment::factory()->count(2)->create(['task_id' => $task->id]);

        $this->assertCount(2, $task->attachments);
        $this->assertInstanceOf(Attachment::class, $task->attachments->first());
    }
}
