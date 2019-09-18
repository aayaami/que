<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;
use App\Question;
use Illuminate\Support\Facades\DB;

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
        $like = Like::where('user_id', auth()->user()->id)->where('question_id', $request->input('question_id'))->first();
        $question = Question::where('id', $request->input('question_id'))->first();

        if($like) {
            if($like->like == true){
                $question->rating = $question->rating - 1;
            } else {
                $question->rating = $question->rating + 1;
            }
            Like::destroy($like->id);
            
            $question->save();
            return redirect('/questions')->with('success', 'Question Liked');
        }

        $like = new Like;
        $like->question_id = $request->input('question_id');
        $like->user_id = auth()->user()->id;
        if($request->input('type') == 'like') {
            $like->like = true;
            $question->rating = $question->rating + 1;
        } else {
            $like->like = false;
            $question->rating = $question->rating - 1;
        }
        $like->save();

        $question->save();

        return response()->json(['success' => $like]);
    }
}
