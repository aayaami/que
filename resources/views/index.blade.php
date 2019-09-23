@extends('layouts.app')

@section('content')
  <div class="row d-flex justify-content-center">
    <div class="jumbotron text-center card bg-light m-5 p-4">
      <h1 class="card-title">Welcome To Que</h1>
      <p class="card-text">In this application you can ask questions and answer questions from others.</p>
      @guest
        <p><a class="btn btn-primary btn-lg" href="/login">Login</a> <a class="btn btn-success btn-lg" href="/register">Register</a></p>
      @endguest
    </div>
  </div>
@endsection