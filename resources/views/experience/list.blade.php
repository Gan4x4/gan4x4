@extends('app')

@section('content')


    <table  class="table project">
    @foreach($experiences as $e)
        <tr>
            <td class="text-center align-middle">
                
                @if ($e->logo)
                    <a href="{{ $e->url }}">
                        <img class="img-sm" src="design/work/{{ $e->logo }}">
                    </a>
                    <br>
                @endif
                {{ $e->start }}

                @if ($e->start != $e->end )
                     - {{ $e->end ?? 'н.в.' }} 
                @endif
            </td>
            <td>
                <b style="text-transform: capitalize;" >{{ $e->position }}</b>
                <br>
                {{ $e->description }}

                <p>
                {!! $e->duties !!}
                </p>
            </td>
        </tr>        
    @endforeach
    </table>    

    
@endsection