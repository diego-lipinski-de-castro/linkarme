<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class UpdateSitesSuggestedColumn extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sites = Site::withTrashed()->get();

        $sites->each(function ($site) {
            $site->suggested = ($site->cost) + ($site->cost * 0.25) + (560.33 * 100);
            $site->save();
        });
    }
}
