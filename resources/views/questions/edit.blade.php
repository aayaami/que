@extends('layouts.app')

@section('content')
  <h3 class="m-3 text-center">Edit Question</h3>
  {!! Form::open(['action' => ['QuestionsController@update', $question->id], 'method' => 'POST', 'files' => true]) !!}
    <div class="form-group">
      {{Form::label('title', 'Title')}}
      {{Form::text('title', $question->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
    </div>
    <div class="form-group">
      {{Form::label('body', 'Body')}}
      {{Form::textarea('body', $question->body, ['class' => 'form-control', 'placeholder' => 'Body Text'])}}
    </div>
    <div class="form-group">
      {{Form::label('images', 'Update Images')}}
      {{Form::file('images[]', ['multiple' => 'multiple'])}}
    </div>
    {!! Form::token() !!}
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
@endsection