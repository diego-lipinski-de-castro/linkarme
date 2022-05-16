<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $this->call(CategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SellerSeeder::class);
    }
}
