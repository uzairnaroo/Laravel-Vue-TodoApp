<?php

namespace App\Repositories\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks(Request $request)
    {
        $query = Task::where('user_id', Auth::id());

        // Apply Filters
        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->is_completed);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('due_date_from') && $request->has('due_date_to')) {
            $query->whereBetween('due_date', [$request->due_date_from, $request->due_date_to]);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('is_archived')) {
            $query->where('is_archived', $request->is_archived);
        }

        // Apply Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate(6);
    }

    public function getTaskById($id)
    {
        return Task::where('user_id', Auth::id())->findOrFail($id);
    }

    public function createTask(array $data)
    {
        return Task::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ]);
    }

    public function updateTask($id, array $data)
    {
        $task = $this->getTaskById($id);
        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = $this->getTaskById($id);
        $task->delete();
    }

    public function markAsCompleted($id)
    {
        $task = $this->getTaskById($id);
        $task->is_completed = true;
        $task->completed_at = now();
        $task->save();
        return $task;
    }

    public function markAsIncomplete($id)
    {
        $task = $this->getTaskById($id);
        $task->is_completed = false;
        $task->completed_at = null;
        $task->save();
        return $task;
    }

    public function archiveTask($id)
    {
        $task = $this->getTaskById($id);
        $task->is_archived = true;
        $task->archived_at = now();
        $task->save();
        return $task;
    }

    public function restoreTask($id)
    {
        $task = $this->getTaskById($id);
        $task->is_archived = false;
        $task->archived_at = null;
        $task->save();
        return $task;
    }
}
