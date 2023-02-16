<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = Project::query()
            ->ofClient(auth()->id())
            ->withCount('sites')
            ->get();

        return Inertia::render('Client/Projects/Index', [
            'projects' => $projects,
        ]);
    }
}
