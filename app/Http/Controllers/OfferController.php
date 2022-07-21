<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::query()
            ->with([
                'seller',
                'site' => function ($q) {
                    $q->with('seller');
                },
            ])
            ->get();

        return view('sites.offers', [
            'offers' => $offers,
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
