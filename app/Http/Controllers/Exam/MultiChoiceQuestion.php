<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\StandardMultiChoice as Standard;
use App\Models\AnswerMultiChoice as Answer;
use App\Models\Answer as Answers;

/**
 * Exam true-false question
 */
class MultiChoiceQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'multi-choice';

    // show all true-false questions
    public function show(ExamAuth $auth)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        // answers
        $answers = Answers::select('answer_multi_choice.*', 'answers.question')
            ->join('answer_multi_choice', 'answers.id', '=', 'answer_multi_choice.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');
        $questions = Question::select('questions.*', 'question_multi_choice.order', 'question_multi_choice.option')
            ->join('question_multi_choice', 'questions.ref', '=', 'question_multi_choice.id')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('questions.id', 'asc')
            ->get()
            ->reduce(function($qs, $q) use($answers) {
                if (isset($qs[$q->id])) {
                    $qs[$q->id]->options[] =  (object)[
                        'order' => $q->order,
                        'option' => $q->option
                    ];
                } else {
                    $qs[$q->id] = (object)[
                        'id' => $q->id,
                        'description' => $q->description,
                        'score' => $q->score,
                        'source' => $q->source,
                        'options' => [
                            (object)[
                                'order' => $q->order,
                                'option' => $q->option,
                            ]
                        ],
                        'answer' => isset($answers[$q->id]) ? $answers[$q->id]->answer : 0
                    ];
                }
                return $qs;
            }, []);

        return view('exam.multi-choice', [
            'active' => $this->type,
            'auth' => $auth,
            'questions' => $questions,
        ]);
    }

    // save answer
    public function save(ExamAuth $auth, Request $request)
    {
        if (!$auth->running) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::select('questions.*', 'standard_multi_choice.answer')
            ->join('standard_multi_choice', 'standard_multi_choice.id', '=', 'questions.ref')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->get();

        $oldAnswers = Answers::select('answer_multi_choice.*', 'answers.question')
            ->join('answer_multi_choice', 'answers.id', '=', 'answer_multi_choice.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');

        foreach ($questions as $q) {
            $answers = $request->input($q->id);
            $oldAnswer = isset($oldAnswers[$q->id]) ? $oldAnswers[$q->id]->answer : null;
            if ($answers || $oldAnswer) {
                if (is_null($answers)) $answers = [];
                $answer = 0;
                foreach ($answers as $a) {
                    $answer |= 1 << $a;
                }
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
                $answerObject->answer = $answer;
                if ($answer === $q->answer) {
                    $answersObject->score = $q->score;
                } else {
                    $answersObject->score = 0;
                }
                $answersObject->save();
                $answerObject->save();
            }
        }

        return back();
    }
}
