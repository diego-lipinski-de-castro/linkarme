<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Site;
use Spatie\QueryBuilder\QueryBuilder;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = QueryBuilder::for(Site::class)
            ->with('category')
            ->defaultSort('url')
            ->allowedSorts(['url', 'da', 'dr', 'traffic', 'tf'])
            ->allowedFilters(['url', 'category_id'])
            ->paginate(15)
            ->appends(request()->query());

        $categories = Category::orderBy('name')->get();

        return view('client.sites.index', [
            'sites' => $sites,
            'categories' => $categories,
        ]);
    }
}
