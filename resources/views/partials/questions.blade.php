<div id="questions">
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
</div>

<script type="text/javascript">

  // $.ajaxSetup({
  //   headers: {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //   }
  // });
  
  $(".like").click(function(e){

    e.preventDefault();

    question_id = e.target.dataset['questionid']
    type = e.target.dataset['type']
    search = $('#search').val()

    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'POST',
        url:"{{ route('ajaxRequest.post') }}",
        data:{question_id: question_id, type: type, search: search},
        success:function(response){
          // $('#data-holder').html(response.html);
          $('#questions').replaceWith(response.html);
        }
    });
  
  });
</script>