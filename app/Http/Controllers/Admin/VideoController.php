<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Experience;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all()->sortByDesc('created_at');
        return view('admin.videos.index', ['videos' => $videos]);
    }

    public function create()
    {
        $experiences = Experience::query()->orderByDesc('start')->get();
        return view('admin.videos.create', [
            'video' => new Video(),
            'experiences' => $experiences,
        ]);
    }

    public function store(Request $request)
    {
        $video = new Video();
        $video->fill($this->withRequiredDefaults($this->payload($request)));
        $video->save();

        return redirect()->route('admin.videos.edit', [$video->id]);
    }

    public function edit(Video $video)
    {
        $experiences = Experience::query()->orderByDesc('start')->get();
        return view('admin.videos.edit', [
            'video' => $video,
            'experiences' => $experiences,
        ]);
    }

    public function update(Request $request, Video $video)
    {
        $video->fill($this->withRequiredDefaults($this->payload($request), $video));
        $video->save();

        return redirect()->route('admin.videos.edit', [$video->id]);
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index');
    }

    private function payload(Request $request): array
    {
        $request->validate([
            'experience_id' => 'nullable|integer|exists:experiences,id',
        ]);

        $fields = [
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'url', 'image', 'code', 'experience_id',
        ];
        $input = $request->all();
        $data = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $input)) {
                $data[$field] = $input[$field];
            }
        }

        if (array_key_exists('experience_id', $data) && $data['experience_id'] === '') {
            $data['experience_id'] = null;
        }

        return $data;
    }

    private function withRequiredDefaults(array $data, ?Video $video = null): array
    {
        $required = [
            'name_en',
            'name_ru',
            'description_en',
            'description_ru',
            'url',
            'image',
            'code',
        ];

        foreach ($required as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                continue;
            }

            if ($video) {
                $data[$field] = (string) ($video->getAttributes()[$field] ?? '');
            } else {
                $data[$field] = '';
            }
        }

        return $data;
    }
}
