<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article' => '',
            'status' => Author::STATUS_PENDING,
            //'post_id' => 1,
            'user_id' => User::factory()->create(['user_type' => User::AUTHOR])->id,
        ];
    }
}
