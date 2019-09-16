@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/questions/create" class="btn btn-primary mb-3">
                        Create Post
                    </a>
                    <h3>Your Questions</h3>
                    @if(count($questions) > 0)
                        <table class="table table-striped">
                            <tr>
                                <th>Title</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($questions as $question)
                                <tr>
                                    <th>{{$question->title}}</th>
                                    <th><a href="/posts/{{$question->id}}/edit" class="btn btn-primary">Edit</a></th>
                                    <th>
                                    {!!Form::open(['action' => ['QuestionsController@destroy', $question->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                        {{Form::hidden('_method', 'DELETE')}}
                                        {{Form::submit('Delete', ['class' => 'btn btn-danger mt-2'])}}
                                    {!!Form::close()!!}
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>You have no questions</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
