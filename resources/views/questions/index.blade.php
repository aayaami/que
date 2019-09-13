@extends('layouts.app')

@section('content')
  <h3 class="m-3 text-center">Questions</h3>
  @if(count($questions) > 0)
    @foreach($questions as $question)
      <div class="jumbotron card bg-light m-2 p-4">
        <h3 class="card-title text-center"><a href="/questions/{{$question->id}}">{{$question->title}}</a></h3>
        <small>Written on {{$question->created_at}}</small>
      </div>
    @endforeach
  @else
    <p>No posts found</p>
  @endif
@endsection