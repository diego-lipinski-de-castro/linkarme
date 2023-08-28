<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\SubmitSiteRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Language;
use App\Models\Seller;
use App\Models\Site;
use App\Models\Team;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

        $seller = Seller::query()
            ->whereNotNull('email')
            ->where('email', request()->query('ref'))
            ->first();

        return Inertia::render('SubmitSite', [
            'categories' => $categories,
            'languages' => $languages,
            'countries' => $countries,
            'sellers' => $sellers,
            'teams' => $teams,
            'types' => $types,
            'coins' => $coins,
            'seller' => $seller,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubmitSiteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubmitSiteRequest $request)
    {
        dd($request->all());

        DB::transaction(function () use($request) {
            
            $site = Site::create(array_merge(
                Arr::except($request->validated(), 'types'), [
                'status' => 'PENDING',
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
