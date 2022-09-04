<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::factory(5)
            ->has(Site::factory(5))
            ->create();
    }
}
