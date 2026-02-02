@extends('app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Edit experience #{{ $experience->id }}</h1>
        <form method="POST" action="{{ route('admin.experiences.destroy', [$experience->id]) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.experiences.update', [$experience->id]) }}">
        @csrf
        @method('PUT')
        @include('admin.experiences._form')
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-link" href="{{ route('admin.experiences.index') }}">Back</a>
    </form>
</div>

@endsection
