<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubmitSiteController extends Controller
{
    public function index()
    {
        return Inertia::render('SubmitSiteSuccess', [

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $sellers = Seller::orderBy('name')->get();
        $teams = Team::orderBy('name')->get();
        $types = Type::orderBy('name')->get();

        $coins = config('coins');

        return Inertia::render('SubmitSite', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
            'teams' => $teams,
            'types' => $types,
            'coins' => $coins,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSiteRequest $request)
    {
        DB::transaction(function () use($request) {
            
            $site = Site::create(array_merge(
                Arr::except($request->validated(), 'types'), [
                'status' => 'APPROVED',
            ]));

            $types = collect($request->validated()['types']);

            $types = $types->where('available', true);

            $types = $types->mapWithKeys(function ($type) {
                return [$type['id'] => [
                    'cost' => Helper::extractNumbersFromString($type['cost']),
                    'sale' => Helper::extractNumbersFromString($type['sale']),
                    'cost_coin' => $type['cost_coin'],
                    'sale_coin' => $type['sale_coin'],
                ]];
            });

            $site->types()->sync($types);
        });

        return redirect()->route('submitSite.success');
    }
}
