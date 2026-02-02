@extends('app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Experience</h1>
        <a class="btn btn-primary" href="{{ route('admin.experiences.create') }}">Add experience</a>
    </div>

    <div class="project">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Position (EN)</th>
                    <th>Position (RU)</th>
                    <th>Years</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($experiences as $experience)
                    <tr>
                        <td>{{ $experience->id }}</td>
                        <td>{{ $experience->getAttributes()['position_en'] ?? '' }}</td>
                        <td>{{ $experience->getAttributes()['position_ru'] ?? '' }}</td>
                        <td>{{ $experience->start }} @if ($experience->start != $experience->end) - {{ $experience->end ?? 'н.в.' }} @endif</td>
                        <td class="text-end">
                            <a href="{{ route('admin.experiences.edit', [$experience->id]) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
