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
                            <img class="img-sm" src="design/work/{{ $e->logo }}" alt="{{ $e->description }}">
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
                    {{ $e->description }}

                    <p>
                    {!! $e->duties !!}
                    </p>
                </td>
            </tr>        
        @endforeach
        </table>    
    </div>

    
@endsection
