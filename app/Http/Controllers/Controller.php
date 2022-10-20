<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function locale($locale)
    {
        if(!in_array($locale, ['en', 'pt'])) {
            abort(404);
        }

        return response()->file(
            lang_path("{$locale}.json"),
        );
    }
}
