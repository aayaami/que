@extends('layouts.app')
@section('content')
  {!! Form::open(['action' => 'QuestionsController@indexSearch', 'method' => 'POST']) !!}
    <div class="form-group">
      {{Form::label('search', 'Search questions by title')}}
      {{Form::text('search', $searchTerm, ['class' => 'form-control', 'placeholder' => 'Question title', 'id' => 'search'])}}
    </div>
    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
  @include('partials.questions')
@endsection


