<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = Site::query()
            ->get();

        $sites->each(function (Site $site) {
            if(
                count($site->types->where('id', 1)) > 0 &&
                count($site->types->where('id', 6)) == 0
            ) {
                $site->types()->attach(6, [
                    'cost' => $site->types->where('id', 1)->first()->pivot->cost,
                    'sale' => $site->types->where('id', 1)->first()->pivot->sale,
                    'cost_coin' => $site->types->where('id', 1)->first()->pivot->cost_coin,
                    'sale_coin' => $site->types->where('id', 1)->first()->pivot->sale_coin,
                ]);
            }
        });
    }
}
