<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\StoresPublicImages;
use App\Http\Controllers\Controller;
use App\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    use StoresPublicImages;

    public function index()
    {
        $experiences = Experience::all()->sortByDesc('start');
        return view('admin.experiences.index', ['experiences' => $experiences]);
    }

    public function create()
    {
        return view('admin.experiences.create', ['experience' => new Experience()]);
    }

    public function store(Request $request)
    {
        $experience = new Experience();
        $experience->fill($this->withRequiredDefaults($this->payload($request)));
        $experience->save();

        return redirect()->route('admin.experiences.edit', [$experience->id]);
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', ['experience' => $experience]);
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->fill($this->withRequiredDefaults($this->payload($request, $experience), $experience));
        $experience->save();

        return redirect()->route('admin.experiences.edit', [$experience->id]);
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('admin.experiences.index');
    }

    private function payload(Request $request, ?Experience $experience = null): array
    {
        $request->validate([
            'start' => 'required|date',
            'logo_file' => 'nullable|file|image|mimes:jpg,jpeg,png,gif,webp,bmp|max:5120',
        ]);

        $fields = [
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'position_en', 'position_ru',
            'duties_en', 'duties_ru',
            'start', 'end', 'url',
        ];
        $input = $request->all();
        $data = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $input)) {
                $data[$field] = $input[$field];
            }
        }
        if (array_key_exists('end', $data) && $data['end'] === '') {
            $data['end'] = null;
        }

        if ($request->hasFile('logo_file')) {
            $previousLogo = $experience?->getAttributes()['logo'] ?? '';
            $nameHint = (string) ($data['name_en'] ?? $data['name_ru'] ?? '');
            $data['logo'] = $this->storePublicImage($request->file('logo_file'), 'work', 'work-logo', $nameHint);
            if ($previousLogo !== '' && $previousLogo !== $data['logo']) {
                $this->removePublicImageIfExists($previousLogo, 'work');
            }
        } else {
            $data['logo'] = (string) ($experience?->getAttributes()['logo'] ?? '');
        }

        return $data;
    }

    private function withRequiredDefaults(array $data, ?Experience $experience = null): array
    {
        $required = [
            'name_en',
            'name_ru',
            'description_en',
            'description_ru',
            'position_en',
            'position_ru',
            'duties_en',
            'duties_ru',
            'url',
            'logo',
        ];

        foreach ($required as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                continue;
            }

            if ($experience) {
                $data[$field] = (string) ($experience->getAttributes()[$field] ?? '');
            } else {
                $data[$field] = '';
            }
        }

        return $data;
    }
}
