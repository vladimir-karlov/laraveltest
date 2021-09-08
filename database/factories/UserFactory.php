<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory {

    protected $model = User::class;

    public function definition()
    : array {
		// $faker->unique()->name,
        return [
	        'name' => $faker->firstName + '' + $faker->lastName,
	        'email' => $faker->unique()->safeEmail,
	        'email_verified_at' => now(),
	        'password' => bcrypt('laraveltest'),
	        'remember_token' => Str::random(10),
        ];
    }
}