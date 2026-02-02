@extends('app')

@section('content')

<div class="container">
    <h1 class="page-title">Admin</h1>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="project">
                <h5>Projects</h5>
                <p>{{ $projects }} items</p>
                <a href="{{ route('admin.projects.index') }}">Manage projects</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="project">
                <h5>Experience</h5>
                <p>{{ $experiences }} items</p>
                <a href="{{ route('admin.experiences.index') }}">Manage experience</a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="project">
                <h5>Videos</h5>
                <p>{{ $videos }} items</p>
                <a href="{{ route('admin.videos.index') }}">Manage videos</a>
            </div>
        </div>
    </div>
</div>

@endsection
