<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generates a new user
        User::create([
            'name' => 'amine',
            'email' => 'bahrimohamedamin7@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
