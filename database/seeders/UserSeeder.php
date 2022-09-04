<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => 'Diego',
            'email' => 'diego@linkarme.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'André',
            'email' => 'andre@linkarme.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Max',
            'email' => 'max@linkarme.com',
            'password' => bcrypt('password'),
        ]);
    }
}
