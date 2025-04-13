<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,  // Generates a random title
            'description' => $this->faker->paragraph,  // Generates a random description
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),  // Random priority
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),  // Random due date within the next month
        ];
    }
}
