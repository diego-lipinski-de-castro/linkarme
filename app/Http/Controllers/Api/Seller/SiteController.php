<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function check(Request $request)
    {
        $url = $request->query('url');

        $url = Str::contains($url, '://') ?
            str_replace('www.', '', parse_url($url, PHP_URL_HOST)) :
            str_replace('www.', '', parse_url($url, PHP_URL_PATH));

        $site = Site::query()
            ->with(['types'])
            ->where('url', $url)
            ->first();

        return response()->json([
            'site' => $site,
        ]);
    }
}
