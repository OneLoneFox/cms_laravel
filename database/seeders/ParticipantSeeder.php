<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(25)
            ->create(['user_type' => User::PARTICIPANT]);
        
        $participants = User::where('user_type', User::PARTICIPANT)->get()->map(function($user){
            return $user->id;
        });
        Post::first()->participants()
            ->attach($participants, ['payment_verified' => false]);
    }
}
