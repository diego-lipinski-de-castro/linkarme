<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(SellerSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
