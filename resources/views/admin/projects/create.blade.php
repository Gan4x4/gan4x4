@extends('app')

@section('content')

<div class="container">
    <h1 class="page-title">Add project</h1>
    <form method="POST" action="{{ route('admin.projects.store') }}">
        @csrf
        @include('admin.projects._form')
        <button type="submit" class="btn btn-primary">Create</button>
        <a class="btn btn-link" href="{{ route('admin.projects.index') }}">Cancel</a>
    </form>
</div>

@endsection
