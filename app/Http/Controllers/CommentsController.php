<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'body' => 'required'
        ]);
        // Create comment
        $comment = new Comment;
        $comment->body = $request->input('body');
        $comment->question_id = $request->input('question_id');
        $comment->user_id = auth()->user()->id;
        $comment->save();

        return redirect('/questions'.'/'.$comment->question_id)->with('success', 'Comment Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);

        // Check for correct user
        if(auth()->user()->id !== $comment->user_id){
            return view('/comments')->with('error', 'Unauthorized Page');
        }
        return view('comments.edit')->with('comment', $comment);
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
            'body' => 'required'
        ]);
        // Update comment
        $comment = Comment::find($id);
        $comment->body = $request->input('body');
        $comment->save();

        return redirect("/questions/$comment->question_id")->with('success', 'Comment Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $question_id = $request->input('question_id');
        $comment = Comment::find($id);

        // Check for correct user
        if(auth()->user()->id !== $comment->user_id){
            return view('/questions')->with('error', 'Unauthorized Page');
        }
        $comment->delete();
        return redirect("/questions/$question_id")->with('success', 'Comment Removed');
    }
}
