<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => rand(1, User::query()->count()),
            'title' => $this->faker->sentence,
            'status' => $this->faker->boolean,
        ];
    }
}
