<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Seller;
use App\Models\Site;
use Inertia\Inertia;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coins = config('coins');

        $sellers = Seller::query()
            ->orderBy('name')
            ->paginate();
        
        return Inertia::render('Sellers/IndexNew', [
            'sellers' => $sellers,
            'coins' => $coins,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $coins = config('coins');

        return Inertia::render('Sellers/CreateNew', [
            'coins' => $coins,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSellerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellerRequest $request)
    {
        Seller::create($request->validated());

        return redirect()->route('sellers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        $coins = config('coins');

        $sites = Site::query()
            ->ofSeller($seller->id)
            ->paginate();

        return Inertia::render('Sellers/Show', [
            'seller' => $seller,
            'sites' => $sites,
            'coins' => $coins,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        $coins = config('coins');

        return Inertia::render('Sellers/EditNew', [
            'seller' => $seller->load('tokens'),
            'coins' => $coins,
            'token' => session('token'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellerRequest  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->validated());

        return redirect()->route('sellers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        $seller->delete();

        return back();
    }

    public function generateApiToken(Seller $seller)
    {
        $seller->tokens()->delete();

        $token = $seller->createToken('API Token');

        return back()->with('token', $token->plainTextToken);
    }
}
