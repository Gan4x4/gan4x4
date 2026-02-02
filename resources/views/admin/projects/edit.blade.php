@extends('app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Edit project #{{ $project->id }}</h1>
        <form method="POST" action="{{ route('admin.projects.destroy', [$project->id]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.projects.update', [$project->id]) }}">
        @csrf
        @method('PUT')
        @include('admin.projects._form')
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-link" href="{{ route('admin.projects.index') }}">Back</a>
    </form>
</div>

@endsection
