<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();
        for ($index = 1; $index <= 100000; $index++) {
            Transaction::create([
                'user_id' => User::find(1)->id, // Generates a new User if none exists
                'location_id' => $locations->random()->id, // Generates a new Location if none exists
                'amount' => fake()->randomFloat(2, 1, 1000),
                'transaction_date' => fake()->dateTimeThisYear,
            ]);
        }
    }
}
