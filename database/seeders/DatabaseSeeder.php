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
        $firstUser = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe',
            'password' => bcrypt('12345'),
        ]);
        $secondUser = User::create([
            'first_name' => 'Richard',
            'last_name' => 'Roe',
            'username' => 'richard.roe',
            'password' => bcrypt('12345'),
        ]);
        $thirdUser = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Poe',
            'username' => 'jane.poe',
            'password' => bcrypt('12345'),
        ]);

        $firstUser->loginToken()->create();
        $secondUser->loginToken()->create();
        $thirdUser->loginToken()->create();
    }
}
