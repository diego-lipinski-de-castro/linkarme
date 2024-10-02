<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Site;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'selected' => ['required', 'array', 'min:0', 'max:100'],
            'selected.*' => ['required', 'string'],
        ]);

        $cart = Cart::query()
            ->updateOrCreate([
                'client_id' => auth('client')->id()
            ]);

        $sites = collect();

        foreach($input['selected'] as $site) {
            $sites->push(Site::query()
                ->where('url', $site)
                ->first());
        }

        $cart->items()->sync($sites->pluck('id'));

        return back();
    }
}
