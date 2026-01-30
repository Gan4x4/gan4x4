@extends('app')

@section('content')

    <form method="POST" action="{{ route('projects.update',[$p->id]) }}">
          
          @method('PATCH')
          @csrf
          <div class="form-group">    
              <label for="name">Project name:</label>
              <input type="text" class="form-control" name="name" value="{{ $p->name }}" />
          </div>
 
          <div class="form-group">
              <label for="start">Start:</label>
              <input type="date" class="form-control" name="start" value="{{ $p->getAttributes()['start'] }}" />
          </div>
          
          <div class="form-group">
              <label for="end">End:</label>
              <input type="date" class="form-control" name="end" value="{{ $p->getAttributes()['end'] }}" />
          </div>
          
          <div class="form-group">    
              <label for="url">URL:</label>
              <input type="text" class="form-control" name="url" value="{{ $p->url }}" />
          </div>
 
          <div class="form-group">
              <label for="description">Description:</label>
              <textarea class="form-control" name="description" rows="5">{!! $p->description !!}</textarea>
          </div>
          
          <div class="form-group">    
              <label for="skill">Skill:</label>
              <input type="text" class="form-control" name="skill" value="{{ json_encode($p->skill) }}"/>
          </div>
          
          <div class="form-group">
              <label for="description">Links:</label>
              <textarea class="form-control" name="links" rows="5">{!! json_encode($p->links) !!}</textarea>
          </div>
          
          
          <button type="submit" class="btn btn-primary">Save</button>
    </form>


@endsection