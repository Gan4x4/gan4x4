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
                            <img class="img-sm" src="design/work/{{ $e->logo }}" alt="{{ $e->name }}">
                        </a>
                        <br>
                    @endif
                    {{ $e->start }}

                    @if ($e->start != $e->end )
                         - {{ $e->end ?? 'н.в.' }} 
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
                                <a href="{{ route('projects') }}#project-{{ $project->id }}">{{ $project->name }}</a>
                            @endforeach
                        </div>
                    @endif

                    @if($e->videos->count() > 0)
                        <div class="project-links mt-2">
                            @foreach($e->videos as $video)
                                <a href="{{ route('video') }}#video-{{ $video->id }}">
                                    <i class="fa-solid fa-video me-1" aria-hidden="true"></i>{{ $video->name }}
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
