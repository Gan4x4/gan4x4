@extends('app')

@section('content')


    <div class="container">
        <h1 class="page-title">{{ __('menu.video') }}</h1>
        @foreach($videos as $v)
            <article id="video-{{ $v->id }}" class="row video card-stack" itemscope itemtype="https://schema.org/VideoObject">
                <div class="col"> 
                    <div class="float-start text-center pe-3 ">
                        <a href="{{  $v->url ? $v->url : "#" }}" target="_blank" rel="noopener noreferrer" title="{{ $v->name }}" aria-label="{{ $v->name }}">
                            <img class="img-lg" src="{{ $v->image_url }}" alt="{{ $v->name }}">
                        </a>
                        <br>
                        
                     </div>

                    <b itemprop="name">{{ $v->name }}</b>
                        <br>
                        <div class="md-links-soft" itemprop="description">
                        {!! $v->description !!}
                        </div>
                </div>
            </article>
        @endforeach

    </div>
    
@endsection
