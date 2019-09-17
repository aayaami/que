@extends('layouts.app')

@section('content')
  <h3 class="m-3 text-center">Edit Comment</h3>
  {!! Form::open(['action' => ['CommentsController@update', $comment->id], 'method' => 'POST']) !!}
    <div class="form-group">
      {{Form::label('body', 'Post comment')}}
      {!! Form::hidden('comment_id', $comment->id) !!}
      {{Form::textarea('body', $comment->body, ['class' => 'form-control', 'placeholder' => 'Body Text'])}}
    </div>
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
@endsection