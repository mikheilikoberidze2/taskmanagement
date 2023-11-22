<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequests\AssignTaskRequest;
use App\Http\Requests\TaskRequests\CompleteTaskRequest;
use App\Http\Requests\TaskRequests\StoreTaskRequest;
use App\Http\Requests\TaskRequests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $task = Task::all();
            return TaskResource::collection($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->Store($validatedData);
        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json(['message' => 'Task updated Sucessfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function completeTask (CompleteTaskRequest $request,Task $task){


        if (Auth()->user()->can('assign-task')) {
            $task->update($request->validated());
            return response()->json(['message' => 'Task updated'], 201);
        }
    }

    public function assignTask(AssignTaskRequest $request, Task $task)
    {
        if (!Auth()->user()->can('assign-task')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();
        $result = $this->taskService->assignTask($task, $validatedData['employee_name']);

        return response()->json($result, 200);
    }

}
