<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_tasks_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [['id', 'title', 'description']]]);
    }

    #[Test]
    public function it_creates_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'Task Description',
            'priority' => 'Normal',
            'due_date' => now()->addDays(1)->toDateString(),
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function it_updates_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task Title',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Updated Task Title']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
        ]);
    }

    #[Test]
    public function it_deletes_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
