<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\StoresPublicImages;
use App\Http\Controllers\Controller;
use App\Experience;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    use StoresPublicImages;

    public function index()
    {
        $projects = Project::query()
            ->with('experience')
            ->orderByRaw('COALESCE("end", "start") DESC')
            ->orderByDesc('start')
            ->get();
        return view('admin.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        $experiences = Experience::query()->orderByDesc('start')->get();
        return view('admin.projects.create', [
            'project' => new Project(),
            'experiences' => $experiences,
        ]);
    }

    public function store(Request $request)
    {
        $project = new Project();
        $project->fill($this->payload($request, $project));
        $project->save();

        return redirect()->route('admin.projects.edit', [$project->id]);
    }

    public function edit(Project $project)
    {
        $experiences = Experience::query()->orderByDesc('start')->get();
        return view('admin.projects.edit', [
            'project' => $project,
            'experiences' => $experiences,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $project->fill($this->payload($request, $project));
        $project->save();

        return redirect()->route('admin.projects.index');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index');
    }

    private function payload(Request $request, ?Project $project = null): array
    {
        $request->validate([
            'experience_id' => 'nullable|integer|exists:experiences,id',
        ]);

        $data = $request->only([
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'start', 'end', 'url', 'logo', 'experience_id',
            'skill',
        ]);
        $data['url'] = (string) ($data['url'] ?? '');

        if (array_key_exists('experience_id', $data) && $data['experience_id'] === '') {
            $data['experience_id'] = null;
        }
        $data['links'] = $this->normalizeLinks($request);

        if ($request->hasFile('logo_file')) {
            $request->validate([
                'logo_file' => 'file|image|mimes:jpg,jpeg,png,gif,webp,bmp|max:5120',
            ]);
            $previousLogo = $project?->getAttributes()['logo'] ?? '';
            $nameHint = (string) ($data['name_en'] ?? $data['name_ru'] ?? '');
            $data['logo'] = $this->storePublicImage($request->file('logo_file'), 'projects', 'project-logo', $nameHint);
            if ($previousLogo !== '' && $previousLogo !== $data['logo']) {
                $this->removePublicImageIfExists($previousLogo, 'projects');
            }
        }

        return $data;
    }

    private function normalizeLinks(Request $request): ?array
    {
        $urlItems = $request->input('links_url', []);
        $ruItems = $request->input('links_ru', []);
        $enItems = $request->input('links_en', []);

        if (!is_array($urlItems)) {
            $urlItems = [];
        }
        if (!is_array($ruItems)) {
            $ruItems = [];
        }
        if (!is_array($enItems)) {
            $enItems = [];
        }

        $count = max(count($urlItems), count($ruItems), count($enItems));
        if ($count === 0) {
            return null;
        }        

        $normalized = [];
        for ($i = 0; $i < $count; $i++) {
            $url = trim((string) ($urlItems[$i] ?? ''));
            $ruLabel = trim((string) ($ruItems[$i] ?? ''));
            $enLabel = trim((string) ($enItems[$i] ?? ''));

            if ($url === '' && $ruLabel === '' && $enLabel === '') {
                continue;
            }

            if ($url === '') {
                throw ValidationException::withMessages([
                    'links_url' => 'Link URL is required for each non-empty link row.',
                ]);
            }

            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw ValidationException::withMessages([
                    'links_url' => "Invalid URL in links row " . ($i + 1) . ".",
                ]);
            }

            $normalized[] = [$url, $ruLabel, $enLabel];
        }

        return $normalized === [] ? null : $normalized;
    }
}
