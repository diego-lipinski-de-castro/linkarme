<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\Api\SiteResource;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return AnonymousResourceCollection<LengthAwarePaginator<SiteResource>>
     */
    public function index(Request $request)
    {
        $sites = Site::query()
            ->paginate(10);

        return SiteResource::collection($sites);
    }
}
