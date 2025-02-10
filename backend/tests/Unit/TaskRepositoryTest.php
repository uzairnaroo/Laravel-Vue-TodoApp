<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\Task\TaskRepository;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
    }

    #[Test]
    public function it_can_get_all_tasks_for_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        Task::factory()->count(3)->create(['user_id' => $user->id]);
        Task::factory()->count(2)->create(); // Tasks for other users

        $request = new Request();
        $tasks = $this->taskRepository->getAllTasks($request);

        $this->assertCount(3, $tasks);
    }

    #[Test]
    public function it_can_get_task_by_id_for_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $foundTask = $this->taskRepository->getTaskById($task->id);

        $this->assertEquals($task->id, $foundTask->id);
    }

    #[Test]
    public function it_can_create_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'high',
            'due_date' => now()->addDays(7),
        ];

        $task = $this->taskRepository->createTask($data);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $user->id,
            'title' => 'Test Task',
        ]);
    }

    #[Test]
    public function it_can_update_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $data = ['title' => 'Updated Task Title'];

        $updatedTask = $this->taskRepository->updateTask($task->id, $data);

        $this->assertEquals('Updated Task Title', $updatedTask->title);
    }

    #[Test]
    public function it_can_delete_a_task_for_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->taskRepository->deleteTask($task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_can_mark_a_task_as_completed()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id, 'is_completed' => false]);

        $completedTask = $this->taskRepository->markAsCompleted($task->id);

        $this->assertTrue($completedTask->is_completed);
        $this->assertNotNull($completedTask->completed_at);
    }

    #[Test]
    public function it_can_mark_a_task_as_incomplete()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id, 'is_completed' => true]);

        $incompleteTask = $this->taskRepository->markAsIncomplete($task->id);

        $this->assertFalse($incompleteTask->is_completed);
        $this->assertNull($incompleteTask->completed_at);
    }

    #[Test]
    public function it_can_archive_a_task()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id, 'is_archived' => false]);

        $archivedTask = $this->taskRepository->archiveTask($task->id);

        $this->assertTrue($archivedTask->is_archived);
        $this->assertNotNull($archivedTask->archived_at);
    }

    #[Test]
    public function it_can_restore_an_archived_task()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['user_id' => $user->id, 'is_archived' => true]);

        $restoredTask = $this->taskRepository->restoreTask($task->id);

        $this->assertFalse($restoredTask->is_archived);
        $this->assertNull($restoredTask->archived_at);
    }
}
