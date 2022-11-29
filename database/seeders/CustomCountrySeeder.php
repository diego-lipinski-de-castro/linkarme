<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create(['name' => 'Argentina']);
        Country::create(['name' => 'Bolívia']);
        Country::create(['name' => 'Brasil']);
        Country::create(['name' => 'Chile']);
        Country::create(['name' => 'Colômbia']);
        Country::create(['name' => 'Costa Rica']);
        Country::create(['name' => 'Cuba']);
        Country::create(['name' => 'Equador']);
        Country::create(['name' => 'El Salvador']);
        Country::create(['name' => 'Guatemala']);
        Country::create(['name' => 'Haiti']);
        Country::create(['name' => 'Honduras']);
        Country::create(['name' => 'México']);
        Country::create(['name' => 'Nicarágua']);
        Country::create(['name' => 'Panamá ']);
        Country::create(['name' => 'Paraguai']);
        Country::create(['name' => 'Peru']);
        Country::create(['name' => 'República Dominicana']);
        Country::create(['name' => 'Uruguai']);
        Country::create(['name' => 'Venezuela']);
        Country::create(['name' => 'Polonia']);
        Country::create(['name' => 'Japão']);
        Country::create(['name' => 'Portugal']);
        Country::create(['name' => 'Moçambique']);
        Country::create(['name' => 'Angola']);
    }
}
