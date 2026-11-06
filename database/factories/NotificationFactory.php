<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'type' => 'info',
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->paragraph(),
            'data' => null,
            'is_read' => false,
            'read_at' => null,
        ];
    }
}