<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Offer;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OfferController extends Controller
{
    public function index()
    {
        $coins = config('coins');

        $offers = Offer::query()
            ->with([
                'seller',
                'site' => function ($q) {
                  $q->with(['seller', 'types']);
                },
                'types',
            ])
            ->get();

        return Inertia::render('Sites/OffersNew', [
            'offers' => $offers,
            'coins' => $coins,
        ]);
    }

    public function accept(Offer $offer)
    {
        $site = Site::findOrFail($offer->site_id);

        $site->update([
            'seller_id' => $offer->seller_id,

            'cost' => Helper::extractNumbersFromString($offer->cost),
            'cost_coin' => $offer->cost_coin,
        ]);

        $types = $offer->types->mapWithKeys(function ($type) {
            return [$type->id => [
                'cost' => Helper::extractNumbersFromString($type->pivot->cost),
                'cost_coin' => $type->pivot->cost_coin,
            ]];
        });

        $site->types()->sync($types);

        DB::table('offer_type')
            ->where('offer_id', $offer->id)
            ->delete();

        $offer->delete();

        return back();
    }

    public function reject(Offer $offer)
    {
        $offer->delete();

        return back();
    }
}
