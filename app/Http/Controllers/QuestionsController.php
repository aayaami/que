<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Comment;
use App\Like;
use App\Image;

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
    public function index(Request $request)
    {
        if($request->has('search')){
            $searchTerm = $request->input('search');
            $questions = Question::where('title', 'LIKE', "%{$searchTerm}%")->orderBy('rating', 'desc')->with('likes')->get();
        } else {
            $searchTerm = '';
            $questions = Question::with('likes')->orderBy('rating', 'desc')->get();
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
            'body' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        // Create Question
        $question = new Question;
        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->user_id = auth()->user()->id;
        $question->rating = 0;
        $question->save();
        
        // Handle File Upload
        if($request->hasfile('images'))
        {
            foreach($request->file('images') as $file)
            {
                // Get Filename with the extenstion
                $filenameWithExt = $file->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                $file->move(public_path()."/images"."/", $fileNameToStore);  

                $image = new Image;
                $image->user_id = auth()->user()->id;
                $image->title = $fileNameToStore;
                $image->question_id = $question->id;
                $image->save();
            }
        }

        

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

        $question->load('comments.user')->load('images')->get();
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
            'body' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        // Create Question
        $question = Question::find($id);
        $question->title = $request->input('title');
        $question->body = $request->input('body');
        $question->save();

        // Handle File Upload
        if($request->hasfile('images'))
        {
            $images = Image::where('question_id', $question->id)->get();
            if(count($images) > 0){
                foreach($images as $image){
                    \File::delete('images/'.$image->title);
                    $image->delete();
                }
            }
            foreach($request->file('images') as $file)
            {
                // Get Filename with the extenstion
                $filenameWithExt = $file->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload Image
                $file->move(public_path()."/images"."/", $fileNameToStore);  

                $image = new Image;
                $image->user_id = auth()->user()->id;
                $image->title = $fileNameToStore;
                $image->question_id = $question->id;
                $image->save();
            }
        }

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
        $question->likes()->delete();
        $question->delete();

        return redirect('/questions')->with('success', 'Comment Removed');
    }
}
