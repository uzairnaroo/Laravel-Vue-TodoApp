<?php

namespace App\Repositories\Task;

use App\Models\Task;
use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getAllTasks(Request $request);
    public function getTaskById($id);
    public function createTask(array $data);
    public function updateTask($id, array $data);
    public function deleteTask($id);
    public function markAsCompleted($id);
    public function markAsIncomplete($id);
    public function archiveTask($id);
    public function restoreTask($id);
}
