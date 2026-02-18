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
        $project->fill($this->payload($request));
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
        $data['links'] = $this->normalizeLinks($request->input('links'));

        if ($request->hasFile('logo_file')) {
            $request->validate([
                'logo_file' => 'file|image|mimes:jpg,jpeg,png,gif,webp,bmp|max:5120',
            ]);
            $data['logo'] = $this->storePublicImage($request->file('logo_file'), 'design/projects', 'project-logo');
        }

        return $data;
    }

    private function normalizeLinks($rawLinks): ?array
    {
        if ($rawLinks === null) {
            return null;
        }

        if (is_array($rawLinks)) {
            return $rawLinks;
        }

        $rawLinks = trim((string) $rawLinks);
        if ($rawLinks === '') {
            return null;
        }

        $decoded = json_decode($rawLinks, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'links' => 'Links must be a valid JSON array.',
            ]);
        }

        // Backward compatibility: DB can contain a JSON-encoded string.
        if (is_string($decoded)) {
            $decodedAgain = json_decode($decoded, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw ValidationException::withMessages([
                    'links' => 'Links must be a JSON array of [url, ru_label, en_label].',
                ]);
            }
            $decoded = $decodedAgain;
        }

        if (!is_array($decoded)) {
            throw ValidationException::withMessages([
                'links' => 'Links must be a JSON array of [url, ru_label, en_label].',
            ]);
        }

        return $decoded;
    }
}
