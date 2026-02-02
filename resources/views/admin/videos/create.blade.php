@extends('app')

@section('content')

<div class="container">
    <h1 class="page-title">Add video</h1>
    <form method="POST" action="{{ route('admin.videos.store') }}">
        @csrf
        @include('admin.videos._form')
        <button type="submit" class="btn btn-primary">Create</button>
        <a class="btn btn-link" href="{{ route('admin.videos.index') }}">Cancel</a>
    </form>
</div>

@endsection
