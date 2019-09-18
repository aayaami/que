<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Comment;

class QuestionsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchTerm = '';
        // $questions = Question::orderBy('created_at', 'desc')->get();
        $questions = Question::with('likes')->orderBy('rating', 'desc')->get();
        // $questions = likes();
        return view('questions.index')->with('questions', $questions)->with('searchTerm', $searchTerm);
    }

    /**
     * Display a listing of the resource by search.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearch(Request $request)
    {
        $searchTerm = trim($request->input('search'));

        if($searchTerm == ''){
            $questions = Question::with('likes')->orderBy('rating', 'desc')->get();
        } else {
            $questions = Question::where('title', 'LIKE', "%{$searchTerm}%")->orderBy('rating', 'desc')->with('likes')->get();
        }
        return view('questions.index')->with('questions', $questions)->with('searchTerm', $searchTerm);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
        // Create Question
        $question = new Question;
        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->user_id = auth()->user()->id;
        $question->rating = 0;
        $question->save();

        return redirect('/questions')->with('success', 'Question Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);

        $question->load('comments.user')->get();
        return view('questions.show', compact('question'));
        // return view('questions.show')->with('question', $question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);

        // Check for correct user
        if(auth()->user()->id !== $question->user_id){
            return view('/questions')->with('error', 'Unauthorized Page');
        }
        return view('questions.edit')->with('question', $question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);
        // Create Question
        $question = Question::find($id);
        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->save();

        return redirect('/questions')->with('success', 'Question Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);

        // Check for correct user
        if(auth()->user()->id !== $question->user_id){
            return view('/questions')->with('error', 'Unauthorized Page');
        }
        $question->comments()->delete();
        $question->delete();

        return redirect('/questions')->with('success', 'Comment Removed');
    }
}
