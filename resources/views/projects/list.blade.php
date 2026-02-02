@extends('app')

@section('content')


    <div class="container">
        <h1 class="page-title">{{ __('menu.projects') }}</h1>
        @foreach($projects as $p)
            <article class="row project card-stack" itemscope itemtype="https://schema.org/CreativeWork">
                <div class="col"> 
                    <div class="float-start text-center pe-3 project-years">
                        <a href="{{  $p->url ? $p->url : "#" }}" target="_blank">
                            <img class="img-sm" src="design/projects/{{ $p->logo }}" alt="{{ $p->name }}">
                        </a>
                        <br>
                        {{ $p->start }}

                        @if ($p->start != $p->end )
                             - {{ $p->end ?? 'н.в.' }} 
                        @endif
                     </div>

                    <b itemprop="name">{{ $p->name }}</b>
                        <br>
                        <p itemprop="description">
                            {!! $p->description !!}
                        </p>

                        @if (is_array($p->links) && count($p->links) > 0) 
                            <div class="project-links">
                                @foreach($p->links as $l)
                                    <a href="{{ $l->url }}">{{ $l->description }}</a>
                                @endforeach
                            </div>
                        @endif

                        <div class="project-tags">
                            @foreach($p->skill as $s)
                                <span class="badge bg-light text-dark" >{{ $s }}</span>
                            @endforeach
                        </div>
                </div>
            </article>
        @endforeach

    
@endsection
