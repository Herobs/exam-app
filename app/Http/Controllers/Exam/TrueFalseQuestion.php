<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\StandardTrueFalse as Standard;
use App\Models\AnswerTrueFalse as Answer;
use App\Models\Answer as Answers;

/**
 * Exam true-false question
 */
class TrueFalseQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'true-false';

    // show all true-false questions
    public function show(ExamAuth $auth)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        $answers = Answers::select('answer_true_false.*', 'answers.question')
            ->join('answer_true_false', 'answers.id', '=', 'answer_true_false.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');

        return view('exam.true-false', [
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
        $questions = Question::select('questions.*', 'standard_true_false.answer')
            ->join('standard_true_false', 'standard_true_false.id', '=', 'questions.ref')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->get();

        $oldAnswers = Answers::select('answer_true_false.*', 'answers.question')
            ->join('answer_true_false', 'answers.id', '=', 'answer_true_false.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');

        foreach ($questions as $q) {
            $answer = $request->input($q->id);
            $oldAnswer = isset($oldAnswers[$q->id]) ? $oldAnswers[$q->id]->answer : null;
            if ($answer || $oldAnswer) {
                // skip for performence
                if ($answer === $oldAnswer) continue;
                $answersObject = Answers::where('student', $auth->student->id)
                    ->where('question', $q->id)
                    ->first();
                if (is_null($answersObject)) {
                    $answersObject = new Answers;
                    $answersObject->student = $auth->student->id;
                    $answersObject->question = $q->id;
                    $answersObject->type = $this->type;
                    $answersObject->save();

                    $answerObject = new Answer;
                    $answerObject->id = $answersObject->id;
                } else {
                    $answerObject = Answer::find($answersObject->id);
                }
                $answersObject->submit = Carbon::now();
                if ($answer === $q->answer) {
                    $answersObject->score = $q->score;
                } else {
                    $answersObject->score = 0;
                }
                $answersObject->save();

                $answerObject->answer = $answer;
                $answerObject->save();
            }
        }

        return back();
    }
}
