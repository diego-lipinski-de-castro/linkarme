<?php

namespace App\Http\Controllers;

use Dedoc\Scramble\Attributes\ExcludeRouteFromDocs;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    #[ExcludeRouteFromDocs]
    public function locale($locale)
    {
        if(!in_array($locale, ['en', 'pt', 'es'])) {
            abort(404);
        }

        return response()->file(
            lang_path("{$locale}.json"),
        );
    }
}
