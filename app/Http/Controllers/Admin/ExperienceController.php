<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
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
        $experience->fill($this->payload($request));
        $experience->save();

        return redirect()->route('admin.experiences.edit', [$experience->id]);
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', ['experience' => $experience]);
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->fill($this->payload($request));
        $experience->save();

        return redirect()->route('admin.experiences.edit', [$experience->id]);
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return redirect()->route('admin.experiences.index');
    }

    private function payload(Request $request): array
    {
        return $request->only([
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'position_en', 'position_ru',
            'duties_en', 'duties_ru',
            'start', 'end', 'url', 'logo',
        ]);
    }
}
