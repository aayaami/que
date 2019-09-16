@extends('layouts.app')

@section('content')
  <h3 class="m-3 text-center">Questions</h3>
  @if(count($questions) > 0)
    @foreach($questions as $question)
      <div class="jumbotron card bg-light m-2 p-4">
        <h3 class="card-title text-center"><a href="/questions/{{$question->id}}">{{$question->title}}</a></h3>
        <small>Written on {{$question->created_at}} by {{$question->user->name}}</small>
      </div>
    @endforeach
  @else
    <p class="text-center">No questions found</p>
  @endif
@endsection