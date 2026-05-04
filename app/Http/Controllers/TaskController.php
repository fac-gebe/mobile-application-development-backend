<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return Task::where('user_id', '=', $user->id)->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => 'nullable|in:low,medium,high,urgent',
            ],
            [
                'title.required' => 'The task title is required.',
                'title.max' => 'The task title cannot be longer than 255 characters.',
                'priority.in' => 'Invalid priority. Allowed values: low, medium, high, urgent.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return response()->json([
            'message' => 'Task created successfully'
        ], 201);
    }

    public function show($id)
    {
        $user = Auth::user();

        $task = Task::find($id);

        if (!$task || $task->user_id !== $user->id) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json($task, 200);
    }

    public function update($id, Request $request)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|in:pending,in_progress,completed',
                'priority' => 'nullable|in:low,medium,high,urgent',
            ],
            [
                'title.required' => 'The task title is required.',
                'title.max' => 'The task title cannot exceed 255 characters.',
                'status.in' => 'Invalid priority. Allowed values: pending, in_progress, completed.',
                'priority.in' => 'Invalid priority. Allowed values: low, medium, high, urgent.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $task->update($validator->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ], 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ], 200);
    }
}
