<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\question;
use App\answer;
use App\reply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // First Time login
        $first_time_login = false;
        if (Auth::user()->first_time_login) {
            $first_time_login = true;
            Auth::user()->first_time_login = 0; 
            Auth::user()->save();   
        }

        // Show Questions
        $questions = question::paginate(9);
        $questions_count = NULL;
        $questions_available = $questions->count();
        if($questions_available > 0){
            foreach($questions as $q){
                $questions_time[] = $q->created_at->diffForHumans();
            }
        }
        else{
            $questions_time = NULL;
        }

        // Count total comment
        if($questions_available > 0){
            $answers = answer::all();
            foreach($answers as $key => $a){
                if($key == 0){
                    $test[$key] = $a->questionId;
                    $total_reply[$key] = count($a->answer_Reply);
                }else {
                    if(in_array($a->questionId,$test)){
                        $k = array_search($a->questionId, $test);
                        $total_reply[$k] = $total_reply[$k] + count($a->answer_Reply); 
                    }else{
                        $test[$key] = $a->questionId;
                        $total_reply[$key] = count($a->answer_Reply);
                    }
                }
            }
            $keys = 0;
            foreach($questions as $key => $question){
                if(count($question->answers) > 0){
                    $total_comment[$key] = count($question->answers) + $total_reply[$keys];
                    $keys += 1;
                } else{
                    $total_comment[$key] = 0;
                }
            }
        }
        else{
            $total_comment = NULL;
        }
        return view('home',compact('questions', 'questions_count', 'questions_time', 'questions_available','total_comment','first_time_login'));
    }

    /**
     * Display the specified resource.
      *@param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request)
    {
        // First Time login
        $first_time_login = false;
        if (Auth::user()->first_time_login) {
            $first_time_login = true;
            Auth::user()->first_time_login = 0; 
            Auth::user()->save();   
        }

        $questions = question::where('title', 'like', '%' . $request->keyword .'%')
            ->orWhere('content', 'like', '%' . $request->keyword .'%')
            ->paginate(9);
        $questions_count = $questions->count();
        $question = question::all();
        $questions_available = $questions_count;
        if($questions_available > 0){
            foreach($question as $q){
                $questions_time[] = $q->created_at->diffForHumans();
            }

            // Count total comment
        
            $answers = answer::all();
            if(count($answers) > 0){
                foreach($answers as $key => $a){
                    if($key == 0){
                        $test[$key] = $a->questionId;
                        $total_reply[$key] = count($a->answer_Reply);
                    }else {
                        if(in_array($a->questionId,$test)){
                            $k = array_search($a->questionId, $test);
                            $total_reply[$k] = $total_reply[$k] + count($a->answer_Reply); 
                        }else{
                            $test[$key] = $a->questionId;
                            $total_reply[$key] = count($a->answer_Reply);
                        }
                    }
                }
                foreach($questions as $key => $q){
                    if(in_array($q->id,$test)){
                        $k = array_search($q->id, $test);
                        $total_comment[$key] = count($q->answers) + $total_reply[$k];
                    } else{
                        $total_comment[$key] = 0;
                    }
                }
            } else{
                $test = NULL;
                $total_comment = NULL;
            }
        }
        else{
            $total_comment = NULL;
            $questions_time = NULL;
        }
        return view("home", compact('questions', 'questions_count','questions_time','questions_available','total_comment','first_time_login'));
    }
}
