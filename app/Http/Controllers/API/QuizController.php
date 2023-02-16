<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\QuizQuestions;
use App\Models\Quiz;
use App\Models\QuizAnswers;
use App\Models\QuizSubmissions;
use Illuminate\Support\Facades\DB;

class QuizController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $quiz = Quiz::with(['questions','questions.answers'])->get();

        return $this->sendResponse($quiz, 'All Quiz Listing');
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
            'description' => 'required',
            'state' => 'required|in:DRAFT,PUBLISHED',
            // 'item.*.description' => 'sometimes|nullable|string|min:60',
        ]);

        $input = $request->all();

        try {
            DB::beginTransaction();

            $quiz = Quiz::create($input);

            if(count($input['questions']) > 0){

                for ($i = 0; $i < count($input['questions']); $i++) { 
                    # code...

                    $question = new QuizQuestions();
                    $question->title = $input['questions'][$i]['title'];
                    $question->mandatory = $input['questions'][$i]['mandatory'];
                    $question->quiz_id = $quiz->id;
                    $question->save();

                    if(count($input['questions'][$i]['answers']) > 0){
                        
                        for ($j=0; $j < count($input['questions'][$i]['answers']); $j++) { 
                            # code...
                           
                            $answers = new QuizAnswers();
                            $answers->title = $input['questions'][$i]['answers'][$j]['title'];
                            $answers->authenticity = $input['questions'][$i]['answers'][$j]['authenticity'];
                            $answers->question_id = $question->id;
                            $answers->save();
                        }
                    }
                }
            }

            DB::commit();

         return $this->sendResponse($quiz, 'Quiz created successfully.');

        } catch (\Throwable $th) {

            DB::rollback();

            return $this->sendError('Something went wrong', $th->getMessage());       
        }
    
    }

    public function submitQuiz(Request $request){

        $this->validate($request, [
            'quiz_id' => 'required',
            'attributes' => 'required',
        ]);

        $input = $request->all();

        $quiz = Quiz::find($input['quiz_id']);

        if(!$quiz) return $this->sendError(true,'Quiz by this ID does not found' );    

        try{

        DB::beginTransaction();

        if($input['attributes']){

            for ($i=0; $i < count($input['attributes']) ; $i++) { 
                # code...

                $quizSubmission = new QuizSubmissions();
                $quizSubmission->quiz_id = $input['quiz_id'];
                $quizSubmission->question_id = $input['attributes'][$i]['question_id'];
                $quizSubmission->answer_id = $input['attributes'][$i]['answer_id'];
                $quizSubmission->save();

            }
        }

        DB::commit();

        return $this->sendResponse(null, 'Quiz submitted successfully.');

    } catch (\Throwable $th) {

        DB::rollback();

        return $this->sendError('Something went wrong', $th->getMessage());       
    }
    }

}
