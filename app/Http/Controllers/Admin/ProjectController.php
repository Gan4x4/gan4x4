<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all()->sortByDesc('start');
        return view('admin.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('admin.projects.create', ['project' => new Project()]);
    }

    public function store(Request $request)
    {
        $project = new Project();
        $project->fill($this->payload($request));
        $project->save();

        return redirect()->route('admin.projects.edit', [$project->id]);
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $project->fill($this->payload($request));
        $project->save();

        return redirect()->route('admin.projects.edit', [$project->id]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index');
    }

    private function payload(Request $request): array
    {
        return $request->only([
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'start', 'end', 'url', 'logo',
            'links', 'skill',
        ]);
    }
}
