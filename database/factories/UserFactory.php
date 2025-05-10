<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password', // password
            'role' => $this->faker->randomElement(['Employee', 'PM', 'HR', 'ADMIN']),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'remember_token' => Str::random(10),
        ];
    }

    // State methods for specific roles
    public function employee()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'Employee',
            ];
        });
    }

    public function pm()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'PM',
            ];
        });
    }

    public function hr()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'HR',
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'ADMIN',
            ];
        });
    }
}
