<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

     /**
     * Display a list of tasks.
     *
     * Get all tasks for the authenticated user.
     *
     * @queryParam is_completed boolean Filter by completed tasks. Example: true
     * @queryParam priority string Filter by task priority. Example: High
     * @queryParam search string Search in task title. Example: Task
     * @response 200 {
     *  "data": [
     *      {
     *          "id": 1,
     *          "title": "Example Task",
     *          "description": "A task example",
     *          "priority": "High",
     *          "due_date": "2025-01-01",
     *          "is_completed": false
     *      }
     *  ]
     * }
     */

    public function index(Request $request)
    {
        $tasks = $this->taskRepository->getAllTasks($request);
        return TaskResource::collection($tasks);
    }

   /**
     * Store a new task.
     *
     * Create a new task for the authenticated user.
     *
     * @bodyParam title string required The title of the task. Example: New Task
     * @bodyParam description string required The description of the task. Example: Task description
     * @bodyParam priority string The priority of the task (Urgent, High, Normal, Low). Example: High
     * @bodyParam due_date string The due date of the task (YYYY-MM-DD). Example: 2025-01-23
     * @response 201 {
     *  "id": 1,
     *  "title": "New Task",
     *  "description": "Task description",
     *  "priority": "High",
     *  "due_date": "2025-01-23",
     *  "created_at": "2025-01-01 00:00:00"
     * }
     */

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskRepository->createTask($request->validated());

        // Handle Tags
        if ($request->has('tags')) {
            $task->attachTags($request->tags);
        }

        // Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $task->attachments()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return new TaskResource($task);
    }

    /**
     * Display a specific task.
     *
     * Fetch the details of a specific task for the authenticated user.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @response 200 {
     *  "id": 1,
     *  "title": "Example Task",
     *  "description": "Detailed task description",
     *  "priority": "High",
     *  "due_date": "2025-01-01",
     *  "is_completed": false,
     *  "created_at": "2025-01-01 00:00:00",
     *  "updated_at": "2025-01-01 00:00:00"
     * }
     */
    public function show($id)
    {
        $task = $this->taskRepository->getTaskById($id);
        return new TaskResource($task);
    }

    /**
     * Update a task.
     *
     * Update the specified task for the authenticated user.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @bodyParam title string The title of the task. Example: Updated Task
     * @bodyParam description string The description of the task. Example: Updated description
     * @bodyParam priority string The priority of the task. Example: Normal
     * @response 200 {
     *  "id": 1,
     *  "title": "Updated Task",
     *  "description": "Updated description",
     *  "priority": "Normal",
     *  "due_date": "2025-01-25",
     *  "updated_at": "2025-01-02 00:00:00"
     * }
     */
    public function update(UpdateTaskRequest $request, $id)
    {

        $task = $this->taskRepository->updateTask($id, $request->validated());

        // Handle Tags
        if ($request->has('tags')) {
            $tags = $request->tags;
            // If tags are strings, ensure conversion to tag model logic here (if needed).
            $task->syncTags($tags);
        }

        // Handle Attachments: Remove any deleted existing attachments
        if ($request->has('existing_attachments')) {
            $existingAttachmentIds = $request->input('existing_attachments');
            $task->attachments()->whereNotIn('id', $existingAttachmentIds)->delete();
        }

        // Handle New Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $task->attachments()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return new TaskResource($task);
    }

    /**
     * Delete a task.
     *
     * Delete the specified task for the authenticated user.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @response 204 {}
     */
    public function destroy($id)
    {
        $this->taskRepository->deleteTask($id);
        return response()->json(null, 204);
    }

    /**
     * Mark the task as completed.
     *
     * Change the task status to completed.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @response 200 {
     *  "id": 1,
     *  "title": "Example Task",
     *  "is_completed": true,
     *  "completed_at": "2025-01-02 00:00:00"
     * }
     */
    public function markAsCompleted($id)
    {
        $task = $this->taskRepository->markAsCompleted($id);
        return new TaskResource($task);
    }

    /**
         * Mark the task as incomplete.
         *
         * Change the task status to incomplete.
         *
         * @urlParam id int required The ID of the task. Example: 1
         * @response 200 {
         *  "id": 1,
         *  "title": "Example Task",
         *  "is_completed": false,
         *  "completed_at": null
         * }
    */
    public function markAsIncomplete($id)
    {
        $task = $this->taskRepository->markAsIncomplete($id);
        return new TaskResource($task);
    }

    /**
     * Archive the task.
     *
     * Archive a specific task for the authenticated user.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @response 200 {
     *  "id": 1,
     *  "title": "Example Task",
     *  "is_archived": true,
     *  "archived_at": "2025-01-02 00:00:00"
     * }
     */
    public function archiveTask($id)
    {
        $task = $this->taskRepository->archiveTask($id);
        return new TaskResource($task);
    }

    /**
     * Restore the archived task.
     *
     * Restore an archived task for the authenticated user.
     *
     * @urlParam id int required The ID of the task. Example: 1
     * @response 200 {
     *  "id": 1,
     *  "title": "Example Task",
     *  "is_archived": false,
     *  "archived_at": null
     * }
     */
    public function restoreTask($id)
    {
        $task = $this->taskRepository->restoreTask($id);
        return new TaskResource($task);
    }
}
