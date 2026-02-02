@extends('app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Projects</h1>
        <a class="btn btn-primary" href="{{ route('admin.projects.create') }}">Add project</a>
    </div>

    <div class="project">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name (EN)</th>
                    <th>Name (RU)</th>
                    <th>Years</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->getAttributes()['name_en'] ?? '' }}</td>
                        <td>{{ $project->getAttributes()['name_ru'] ?? '' }}</td>
                        <td>{{ $project->start }} @if ($project->start != $project->end) - {{ $project->end ?? 'н.в.' }} @endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.projects.edit', [$project->id]) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
