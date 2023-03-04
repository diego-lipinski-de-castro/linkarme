<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Offer;
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
                    $q->with('seller');
                },
            ])
            ->get();

        return Inertia::render('Sites/OffersNew', [
            'offers' => $offers,
            'coins' => $coins,
        ]);
    }

    public function accept(Offer $offer)
    {
        $offer->site()->update([
            'seller_id' => $offer->seller_id,
            'cost' => Helper::extractNumbersFromString($offer->cost),
        ]);

        $offer->delete();

        return back();
    }

    public function reject(Offer $offer)
    {
        $offer->delete();

        return back();
    }
}
