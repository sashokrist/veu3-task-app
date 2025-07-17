<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index() {
        return Task::all();
    }

    public function store(Request $request) {
        $task = Task::create($request->only('title'));
        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task) {
        $task->update($request->only('title', 'completed'));
        return response()->json($task);
    }

    public function destroy(Task $task) {
        $task->delete();
        return response()->json(null, 204);
    }
}

