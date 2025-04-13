<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase; // Ensures a fresh database for each test

    public function test_it_creates_a_task_successfully()
        {
            $payload = [
                'title' => 'Test Task',
                'description' => 'A simple test task',
                'priority' => 'high',
                'due_date' => now()->addDays(3)->toDateTimeString(),
            ];

            $response = $this->postJson('/api/tasks', $payload);

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
            // Create a task with specific values using the factory
            Task::factory()->create([
                'title' => 'Test Task',  // Set the title explicitly
                'description' => 'A simple test task',  // Set the description explicitly
                'priority' => 'high',  // Set the priority explicitly
                'due_date' => now()->addDays(3),  // Set a specific due date
            ]);

            // Make the API request
            $response = $this->getJson('/api/tasks');

            // Assert that the response has a 200 status code and the structure is as expected
            $response->assertStatus(200)
                    ->assertJsonStructure(['data']);

            // Assert that the task with the title "Test Task" is included in the response
            $response->assertJsonFragment([
                'title' => 'Test Task',  // Check if the title is included in the response data
            ]);
        }
    
    public function test_it_updates_a_task_successfully()
        {
            // Arrange: Create a task
            $task = Task::factory()->create();

            // Act: Send a PUT request to update the task
            $response = $this->putJson("/api/tasks/{$task->id}", [
                'title' => 'Updated Task Title',
                'description' => 'Updated Task Description',
                'priority' => 'low',
                'due_date' => now()->addDays(5)->toDateTimeString(),
            ]);

            // Assert: Check response is 200 OK
            $response->assertStatus(200);

            // Assert: Check the task is updated in the database
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
            $response = $this->deleteJson("/api/tasks/{$task->id}");
        
            // Assert: Check response is 200 OK
            $response->assertStatus(200);
        
            // Assert: The task is not found in normal queries
            $this->assertSoftDeleted('tasks', [
                'id' => $task->id,
            ]);
        }
    
    
        

}
