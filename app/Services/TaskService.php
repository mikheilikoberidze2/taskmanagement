<?php


namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskService
{

    public function store(array $userData): Task
    {
        $userData['user_id'] = Auth::id();
        return Task::create($userData);
    }

    public function assignTask(Task $task, string $employeeName)
    {
        $employee = User::where('name', $employeeName)->first();

        if (!$employee) {
            return ['error' => 'Employee not found'];
        }

        $task->assignees()->attach($employee->id);

        return ['message' => 'Task assigned to employee successfully'];
    }
}
