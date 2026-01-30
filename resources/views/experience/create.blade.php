@extends('app', ['selected'=>'education'])

@section('content')

<form method="post" action="{{ action('EducationController@store') }}">
    <input name="_token" type="hidden" value="{{ csrf_token() }}"/>    
    
    
    <button type="submit" class="btn btn-primary">Submit</button>    
</form>

    
@endsection