<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of all tasks with optional filters, sorting, and pagination.
     */
    public function index(Request $request)
    {
        try {
            // Start query with trashed (soft-deleted) tasks included
            $query = Task::withTrashed();

            // Filter by priority if provided
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            // Filter by due date if provided
            if ($request->filled('due_date')) {
                $query->whereDate('due_date', $request->due_date);
            }

            // Apply sorting (default: created_at desc)
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginate results (100 per page)
            $tasks = $query->paginate(100);

            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'No data found.'
                ], 200);
            }

            return response()->json($tasks);
        } catch (\Exception $e) {
            // Catch and return unexpected errors
            return response()->json([
                'message' => 'Failed to fetch tasks.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
                'due_date' => 'nullable|date',
            ]);

            // Create task with validated data
            $task = Task::create($validated);

            return response()->json([
                'message' => 'Task added successfully.',
                'task' => $task
            ], 201); // 201 = Created
        } catch (Throwable $e) {
            $status = $e instanceof ValidationException ? 422 : 500;

            $errors = $e instanceof ValidationException
                ? collect($e->errors())->map(fn($messages) => $messages[0])
                : ['message' => 'Something went wrong.'];

            return response()->json(['errors' => $errors], $status);
        }
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        try {
            // Return the task found via route model binding
            return response()->json($task);
        } catch (Throwable $e) {
            Log::error('Failed to fetch task', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Something went wrong fetching the task.'], 500);
        }
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        try {
            // Validate only fields that are being updated
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
                'due_date' => 'nullable|date',
            ]);

            // Update task with validated data
            $task->update($validated);

            return response()->json(['message' => 'Task updated successfully.'], 200);
        } catch (Throwable $e) {
            Log::error('Failed to update task', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong updating the task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete the specified task from storage.
     */
    public function destroy(Task $task)
    {
        try {
            // Perform soft delete
            $task->delete();

            return response()->json(['message' => 'Task deleted successfully']);
        } catch (Throwable $e) {
            Log::error('Failed to delete task', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Something went wrong deleting the task.'], 500);
        }
    }
}
