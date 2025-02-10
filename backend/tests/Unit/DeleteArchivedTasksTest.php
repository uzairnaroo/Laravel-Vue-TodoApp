<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class DeleteArchivedTasksTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_deletes_tasks_archived_more_than_a_week_ago()
    {
        // Create tasks with different archived_at dates
        Task::factory()->create([
            'is_archived' => true,
            'archived_at' => Carbon::now()->subWeeks(2),
        ]);

        Task::factory()->create([
            'is_archived' => true,
            'archived_at' => Carbon::now()->subDays(5),
        ]);

        // Run the command
        Artisan::call('tasks:delete-archived');

        // Assert that only the task archived more than a week ago is deleted
        $this->assertDatabaseCount('tasks', 1);
        $this->assertDatabaseMissing('tasks', [
            'archived_at' => Carbon::now()->subWeeks(2)->toDateTimeString(),
        ]);
        $this->assertDatabaseHas('tasks', [
            'archived_at' => Carbon::now()->subDays(5)->toDateTimeString(),
        ]);
    }
}
