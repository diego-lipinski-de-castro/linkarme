<?php

namespace Database\Seeders;

use App\Models\Country;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        Country::truncate();

        Schema::enableForeignKeyConstraints();

        $response = (new Client())->get('https://gist.githubusercontent.com/jonasruth/61bde1fcf0893bd35eea/raw/10ce80ddeec6b893b514c3537985072bbe9bb265/paises-gentilicos-google-maps.json');

        $countries = json_decode($response->getBody()->getContents());

        foreach ($countries as $country) {
            Country::create([
                'name' => $country->nome_pais,
            ]);
        }
    }
}
