<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class DeleteArchivedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:delete-archived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete archived tasks that were archived more than a week ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subWeek();

        $tasks = Task::where('is_archived', true)
                     ->where('archived_at', '<', $cutoffDate)
                     ->get();

        $count = $tasks->count();

        foreach ($tasks as $task) {
            $task->delete();
        }

        $this->info("Deleted {$count} archived tasks.");

        return 0;
    }
}
