<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin.videos.create', ['video' => new Video()]);
    }

    public function store(Request $request)
    {
        $video = new Video();
        $video->fill($this->payload($request));
        $video->save();

        return redirect()->route('admin.videos.edit', [$video->id]);
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', ['video' => $video]);
    }

    public function update(Request $request, Video $video)
    {
        $video->fill($this->payload($request));
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
        return $request->only([
            'name_en', 'name_ru',
            'description_en', 'description_ru',
            'url', 'image', 'code',
        ]);
    }
}
