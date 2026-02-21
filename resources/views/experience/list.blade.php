@extends('app')

@section('content')


    <div class="container">
        <h1 class="page-title">{{ __('menu.experience') }}</h1>
        <table class="table project">
        @foreach($experiences as $e)
            <tr>
                <td class="text-center align-middle">
                    
                    @if ($e->logo)
                        <a href="{{ $e->url }}">
                            <img class="img-sm" src="{{ $e->logo_url }}" alt="{{ $e->name }}">
                        </a>
                        <br>
                    @endif
                    {{ $e->start }}

                    @if ($e->start != $e->end )
                         - {{ $e->end ?? __('present') }}
                    @endif
                </td>
                <td>
                    <b class="position-title" >{{ $e->position }}</b>
                    <br>
                    {!! $e->description !!}

                    <p>
                    {!! $e->duties !!}
                    </p>

                    @if($e->projects->count() > 0)
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
                        </div>
                    @endif

                    @if($e->videos->count() > 0)
                        <div class="project-links mt-2">
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
                </td>
            </tr>        
        @endforeach
        </table>    
    </div>

    
@endsection
