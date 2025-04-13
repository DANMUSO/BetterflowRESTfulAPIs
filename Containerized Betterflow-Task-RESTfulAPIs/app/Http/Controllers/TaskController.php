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
    public function index(Request $request)
    {
        try {
            $query = Task::withTrashed();

            // Optional filters
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('due_date')) {
                $query->whereDate('due_date', $request->due_date);
            }
           
            // Optional sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination (if needed, or use ->get() for all)
            $tasks = $query->paginate(10);
            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'No data found.'
                ], 200);
            }
            // Return ONLY the data array
            return response()->json($tasks);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch tasks.',
                'error' => $e->getMessage(), // This will tell us what’s really wrong
            ], 500);
        }
    }
    

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
                'due_date' => 'nullable|date',
            ]);
    
            $task = Task::create($validated);
    
            return response()->json([
                'message' => 'Task added successfuly.',
                'task' => $task
            ], 200);
    
        } catch (Throwable $e) {
            $status = $e instanceof ValidationException ? 422 : 500;
    
            $errors = $e instanceof ValidationException
                ? collect($e->errors())->map(fn($messages) => $messages[0])
                : ['message' => 'Something went wrong.'];
    
            return response()->json(['errors' => $errors], $status);
        }
    }

    public function show(Task $task)
    {
        try {
            return response()->json($task);
        } catch (Throwable $e) {
            Log::error('Failed to fetch task', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Something went wrong fetching the task.'], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
                'due_date' => 'nullable|date',
            ]);

            $task->update($validated);
            return response()->json(['message' => 'Task updated successfuly.'], 200);
        } catch (Throwable $e) {
            Log::error('Failed to update task', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong updating the task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete(); // soft delete
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (Throwable $e) {
            Log::error('Failed to delete task', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Something went wrong deleting the task.'], 500);
        }
    }
}
