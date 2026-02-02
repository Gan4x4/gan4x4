@extends('app')

@section('content')


    <div class="container">
        <h1 class="page-title">{{ __('menu.video') }}</h1>
        @foreach($videos as $v)
            <article class="row video card-stack" itemscope itemtype="https://schema.org/VideoObject">
                <div class="col"> 
                    <div class="float-start text-center pe-3 ">
                        <a href="{{  $v->url ? $v->url : "#" }}" target="_blank">
                            <img class="img-lg" src="design/video/{{ $v->image }}" alt="{{ $v->name }}">
                        </a>
                        <br>
                        
                     </div>

                    <b itemprop="name">{{ $v->name }}</b>
                        <br>
                        <p itemprop="description">
                        {{ $v->description }}
                        </p>
                </div>
            </article>
        @endforeach

    
@endsection
