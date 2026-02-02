@extends('app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Videos</h1>
        <a class="btn btn-primary" href="{{ route('admin.videos.create') }}">Add video</a>
    </div>

    <div class="project">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name (EN)</th>
                    <th>Name (RU)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($videos as $video)
                    <tr>
                        <td>{{ $video->id }}</td>
                        <td>{{ $video->getAttributes()['name_en'] ?? '' }}</td>
                        <td>{{ $video->getAttributes()['name_ru'] ?? '' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.videos.edit', [$video->id]) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
