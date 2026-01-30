@extends('app')

@section('content')


    <div class="container">
    @foreach($videos as $v)
        
        <div class="row video">
            <div class="col "> 
                <div class="float-start text-center pe-3 ">
                    <a href="{{  $v->url ? $v->url : "#" }}" target="_blank">
                        <img class="img-lg" src="design/video/{{ $v->image }}">
                    </a>
                    <br>
                    
                 </div>

                <b>{{ $v->name }}</b>
                    <br>
                    <p>
                    {{ $v->description }}
                    </p>

                    
            </div>
        </div>
        
    @endforeach

    
@endsection