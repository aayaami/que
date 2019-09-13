@extends('layouts.app')

@section('content')
  <a href="/questions" class="btn btn-primary m-2">Go Back</a>
  <div class="jumbotron card bg-light m-2 p-4">
    <h3 class="card-title text-center">{{$question->title}}</h3>
    <p>{{$question->body}}</p>
    <small>Written on {{$question->created_at}}</small>
  </div>
@endsection