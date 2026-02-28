@extends('app')

@section('content')


    <div class="container">
        <h1 class="page-title">{{ __('menu.experience') }}</h1>
        @foreach($experiences as $e)
            <article class="row project card-stack">
                <div class="col">
                    <div class="float-start text-center pe-4 project-years experience-years">
                        @if ($e->logo)
                            <a href="{{ $e->url }}" title="{{ $e->name }}" aria-label="{{ $e->name }}">
                                <img class="img-sm experience-logo" src="{{ $e->logo_url }}" alt="{{ $e->name }}">
                            </a>
                            <br>
                        @endif
                        {{ $e->start }}
                        @if ($e->start != $e->end )
                            - {{ $e->end ?? __('present') }}
                        @endif
                    </div>

                    <b class="position-title">{{ $e->position }}</b>
                    <br>
                    {!! $e->description !!}

                    <p>
                        {!! $e->duties !!}
                    </p>

                    @if($e->projects->count() > 0 || $e->videos->count() > 0)
                        <div class="project-links mt-2">
                            @foreach($e->projects as $project)
                                @php
                                    $projectFullName = (string) $project->name;
                                    $projectLinkLabel = \Illuminate\Support\Str::words($projectFullName, 4, '…');
                                @endphp
                                <a href="{{ route('projects') }}#project-{{ $project->id }}" title="{{ $projectFullName }}" aria-label="{{ $projectFullName }}">
                                    <i class="fa-solid fa-link me-1" aria-hidden="true"></i>{{ $projectLinkLabel }}
                                </a>
                            @endforeach

                            @foreach($e->videos as $video)
                                @php
                                    $videoFullName = (string) $video->name;
                                    $videoLinkLabel = \Illuminate\Support\Str::words($videoFullName, 4, '…');
                                @endphp
                                <a href="{{ route('video') }}#video-{{ $video->id }}" title="{{ $videoFullName }}" aria-label="{{ $videoFullName }}">
                                    <i class="fa-solid fa-video me-1" aria-hidden="true"></i>{{ $videoLinkLabel }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    
@endsection
