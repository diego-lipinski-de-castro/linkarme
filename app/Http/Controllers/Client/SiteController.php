<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Site;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $favorites = auth()->user()->favorites_ids;

        $categories = Category::orderBy('name')->get();

        $sites = QueryBuilder::for(Site::class)
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts(['url', 'da', 'dr', 'traffic', 'tf'])
            ->allowedFilters([
                'url',
                'category_id',
                AllowedFilter::scope('favorites', 'auth_favorites'),
            ])
            ->paginate(15)
            ->appends(request()->query());

        return view('client.sites.index', [
            'sites' => $sites,
            'favorites' => $favorites,
            'categories' => $categories,
        ]);
    }

    public function favorite(Site $site)
    {
        auth()->user()->favorites()->toggle([$site->id]);

        return back();
    }
}
