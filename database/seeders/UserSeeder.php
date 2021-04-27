<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create([
            'name' => 'Master',
            // 'email' => 'admin@example.com',
            'user_type' => User::ADMIN,
            'password' => Hash::make('=vMu@ne22_'),
        ]);
    }
}
