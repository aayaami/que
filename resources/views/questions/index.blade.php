@extends('layouts.app')
@section('content')
  {!! Form::open(['action' => 'QuestionsController@indexSearch', 'method' => 'POST']) !!}
    <div class="form-group">
      {{Form::label('search', 'Search questions by title')}}
      {{Form::text('search', $searchTerm, ['class' => 'form-control', 'placeholder' => 'Question title', 'id' => 'search'])}}
    </div>
    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
  {!! Form::close() !!}
  <h3 class="m-3 text-center">Questions</h3>
  @if(count($questions) > 0)
    @foreach($questions as $question)
      @php
        $canDownvote = true;
        $canUpvote = true;
        foreach($question->likes as $like){
          if(auth()->user()->id == $like->user_id && $like->like == true) {
            $canUpvote = false;
          } elseif (auth()->user()->id == $like->user_id && $like->like == false) {
            $canDownvote = false;
          }
        }
      @endphp
      <div class="jumbotron card bg-light m-2 p-4">
        <h3 class="card-title text-center"><a href="/questions/{{$question->id}}">{{$question->title}}</a></h3>
        <p><b>Rating:</b> {{$question->rating}}</p>
        <button {{$canUpvote ? '' : 'disabled'}} class="btn btn-{{$canUpvote ? 'success' : 'secondary'}} like pull-right mb-2" style="width: 100px;" data-questionid="{{$question->id}}" data-type="{{"like"}}">Upvote</button>
        <button {{$canDownvote ? '' : 'disabled'}} class="btn btn-{{$canDownvote ? 'danger' : 'secondary'}} like pull-right mb-2" style="width: 100px;" data-questionid="{{$question->id}}" data-type="{{"dislike"}}">Downvote</button>
        <small>Written on {{$question->created_at}} by {{$question->user->name}}</small>
      </div>
    @endforeach
  @else
    <p class="text-center">No questions found</p>
  @endif
@endsection


