<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bug;
use Illuminate\Support\Arr;

class BugController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        $input = $request->validate([
            'description' => ['required'],
            'urgent' => ['required', 'boolean'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $bug = Bug::create([
            'client_id' => $user->id,
            'description' => $input['description'],
            'urgent' => $input['urgent'],
        ]);

        if (isset($input['photo'])) {
            $bug->updateBugPhoto($input['photo']);
        }

        return back()->with('status', 'bug-submitted');
    }
}
