@extends('layouts.app')

@section('content')
  <a href="/questions" class="btn btn-primary m-2">Go Back</a>
  <div class="jumbotron card bg-light m-2 p-4">
    <h3 class="card-title text-center">{{$question->title}}</h3>
    <p>{{$question->body}}</p>
    <small>Written on {{$question->created_at}} by {{$question->user->name}}</small>
  </div>
  <hr>
  @if(!Auth::guest())
    @if(Auth::user()->id == $question->user_id)
      <a href="/questions/{{$question->id}}/edit" class="btn btn-primary">Edit</a>

      {!!Form::open(['action' => ['QuestionsController@destroy', $question->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        {{Form::submit('Delete', ['class' => 'btn btn-danger mt-2'])}}
      {!!Form::close()!!}
    @endif
  @endif
@endsection