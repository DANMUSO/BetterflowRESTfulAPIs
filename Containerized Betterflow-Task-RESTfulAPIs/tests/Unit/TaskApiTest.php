<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase; // Ensures a fresh database for each test

    // Helper method to automatically add the API Key to requests
    private function withApiKey(array $headers = [])
    {
        return array_merge($headers, [
            'X-API-KEY' => env('API_KEY'), // Dynamically load from .env
        ]);
    }

    public function test_it_creates_a_task_successfully()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'A simple test task',
            'priority' => 'high',
            'due_date' => now()->addDays(3)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/tasks', $payload, $this->withApiKey());

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Test Task',
                'priority' => 'high',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'A simple test task',
            'priority' => 'high',
        ]);
    }

    public function test_can_fetch_all_tasks()
    {
        // Arrange: Create a task with specific values
        Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'A simple test task',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
        ]);

        // Act: Make the API request
        $response = $this->getJson('/api/tasks', $this->withApiKey());

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment([
                'title' => 'Test Task',
            ]);
    }

    public function test_it_updates_a_task_successfully()
    {
        // Arrange: Create a task
        $task = Task::factory()->create();

        // Act: Send a PUT request to update the task
        $payload = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description',
            'priority' => 'low',
            'due_date' => now()->addDays(5)->toDateTimeString(),
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload, $this->withApiKey());

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description',
            'priority' => 'low',
        ]);
    }

    public function test_it_soft_deletes_a_task()
    {
        // Arrange: Create a task
        $task = Task::factory()->create();

        // Act: Send a DELETE request to soft delete the task
        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $this->withApiKey());

        // Assert
        $response->assertStatus(200);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }
}
