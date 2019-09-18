<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function ajaxRequest()
    {
        return view('ajaxRequest');
    }
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function ajaxRequestPost(Request $request)
    {
        $like = Like::where('user_id', auth()->user()->id)->where('question_id', $request->input('question_id'))-get();

        if($like) {
            $like->destroy($like->id);
            return redirect('/questions')->with('success', 'Question Liked');
        }

        $like = new Like;
        $like->question_id = $request->input('question_id');
        $like->user_id = auth()->user()->id;
        $like->like = true;
        $like->save();

        return redirect('/questions')->with('success', 'Question Liked');
    }
}
