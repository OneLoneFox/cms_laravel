<?php

namespace Database\Factories;

use App\Models\Presentation;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresentationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presentation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'presentation_date' => now()->format('Y-m-d'),
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'location' => 'location i don\'t care',
            // 'post_id' => 1,
        ];
    }
}
