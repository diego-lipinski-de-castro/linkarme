<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        auth()->user()->notes()->updateOrCreate([
            'site_id' => $site->id
        ], [
            'text' => $request->input('text')
        ]);

        return back();
    }
}
