<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateSiteTypesSeeder extends Seeder
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

        $sites->each(function ($site) {

            $types = [];

            if($site->gambling) {
                $types[1] = [
                    'cost' => $site->cost,
                    'sale' => $site->sale,
                    'cost_coin' => $site->cost_coin,
                    'sale_coin' => $site->sale_coin,
                ];
            }

            if($site->cdb) {
                $types[2] = [
                    'cost' => $site->cost,
                    'sale' => $site->sale,
                    'cost_coin' => $site->cost_coin,
                    'sale_coin' => $site->sale_coin,
                ];
            }

            if($site->cripto) {
                $types[3] = [
                    'cost' => $site->cost,
                    'sale' => $site->sale,
                    'cost_coin' => $site->cost_coin,
                    'sale_coin' => $site->sale_coin,
                ];
            }

            $site->types()->sync($types);
        });
    }
}
