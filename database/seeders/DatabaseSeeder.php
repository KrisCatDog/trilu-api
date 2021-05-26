<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $user1 = User::create([
            'username' => 'john.doe',
            'password' => bcrypt('12345'),
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $user2 = User::create([
            'username' => 'richard.roe',
            'password' => bcrypt('12345'),
            'first_name' => 'Richard',
            'last_name' => 'Roe',
        ]);
        $user3 = User::create([
            'username' => 'jane.poe',
            'password' => bcrypt('12345'),
            'first_name' => 'Jane',
            'last_name' => 'Poe',
        ]);


        $user1->loginToken()->create();
        $user2->loginToken()->create();
        $user3->loginToken()->create();
    }
}
