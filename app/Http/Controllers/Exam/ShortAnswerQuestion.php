<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\AnswerShortAnswer as Answer;
use App\Models\Answer as Answers;

/**
 * Exam short-answer question
 */
class ShortAnswerQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'short-answer';

    // show all short-answer questions
    public function show(ExamAuth $auth)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        $answers = Answers::select('answer_short_answer.*', 'answers.question')
            ->join('answer_short_answer', 'answers.id', '=', 'answer_short_answer.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');

        return view('exam.short-answer', [
            'active' => $this->type,
            'auth' => $auth,
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }
    // save answer
    public function save(ExamAuth $auth, Request $request)
    {
        if (!$auth->running) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->pluck('id');

        foreach ($questions as $q) {
            if ($answer = $request->input($q)) {
                $answersObject = Answers::where('student', $auth->student->id)
                    ->where('question', $q)
                    ->first();
                if (is_null($answersObject)) {
                    $answersObject = new Answers;
                    $answersObject->student = $auth->student->id;
                    $answersObject->question = $q;
                    $answersObject->type = $this->type;
                    $answersObject->save();

                    $answerObject = new Answer;
                    $answerObject->id = $answersObject->id;
                } else {
                    $answerObject = Answer::find($answersObject->id);
                }
                $answersObject->submit = Carbon::now();
                $answersObject->score = null;
                $answersObject->save();

                $answerObject->answer = $answer;
                $answerObject->save();
            }
        }

        return back();
    }
}
