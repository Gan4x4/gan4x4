@extends('app')

@section('content')


    <!--  <table  class="table project"> -->
    <div class="container">
    @foreach($projects as $p)
        
        <div class="row project ">
            <div class="col "> 
                <div class="float-start text-center pe-3 ">
                    <a href="{{  $p->url ? $p->url : "#" }}" target="_blank">
                        <img class="img-sm" src="design/projects/{{ $p->logo }}">
                    </a>
                    <br>
                    {{ $p->start }}

                    @if ($p->start != $p->end )
                         - {{ $p->end ?? 'н.в.' }} 
                    @endif
                 </div>

                <b>{{ $p->name }}</b>
                    <br>
                    <p>
                        {!! $p->description !!}
                    </p>

                    @if (is_array($p->links) && count($p->links) > 0) 

                        @foreach($p->links as $l)

                            <a href="{{ $l->url }}">{{ $l->description }}</a>
                        @endforeach
                        <br>
                    @endif

                    @foreach($p->skill as $s)
                        <span class="badge bg-light text-dark" >{{ $s }}</span>
                    @endforeach
            </div>
        </div>
        
    @endforeach
    <!--     </table>    -->

    
@endsection