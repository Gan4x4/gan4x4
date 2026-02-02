@extends('app')

@section('content')

<div class="container">
    <h1 class="page-title">Add experience</h1>
    <form method="POST" action="{{ route('admin.experiences.store') }}">
        @csrf
        @include('admin.experiences._form')
        <button type="submit" class="btn btn-primary">Create</button>
        <a class="btn btn-link" href="{{ route('admin.experiences.index') }}">Cancel</a>
    </form>
</div>

@endsection
