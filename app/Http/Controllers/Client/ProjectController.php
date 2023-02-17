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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'color' => 'required',
        ]);

        Project::create(array_merge($input, [
            'client_id' => auth()->id(),
        ]));

        return back();
    }

    /**
     * Update an existing project resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'color' => 'required',
        ]);

        $project->update($input);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return back();
    }
}
