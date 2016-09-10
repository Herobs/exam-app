<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\StandardBlankFill as Standard;
use App\Models\AnswerBlankFill as Answer;
use App\Models\Answer as Answers;

/**
 * Exam blank-fill question
 */
class BlankFillQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'blank-fill';

    // show all true-false questions
    public function show(ExamAuth $auth)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);

        $answers = Answers::select('answer_blank_fill.*', 'answers.question')
            ->join('answer_blank_fill', 'answers.id', '=', 'answer_blank_fill.id')
            ->where('student', $auth->student->id)
            ->get()
            ->reduce(function($as, $a) {
                $as[$a->question . '-' . $a->order] = $a->answer;
                return $as;
            }, []);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get()
            ->each(function($question) use ($answers, $auth) {
                $order = 0;
                $question->description = preg_replace_callback('/@@/', function($m) use ($answers, $question, $auth, &$order) {
                    $key = $question->id . '-' . $order++;
                    $answer = isset($answers[$key]) ? $answers[$key] : '';
                    return '<input class="form-control" type="text" name="'.$question->id.'[]" value="'.$answer.'"'.pif($auth->ended, ' disabled').' />';
                }, $question->description);
            });

        return view('exam.blank-fill', [
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
        $questions = Question::select('id', 'score')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->get();

        $standards = Standard::select('questions.id', 'standard_blank_fill.order', 'standard_blank_fill.answer')
            ->join('questions', 'questions.ref', '=', 'standard_blank_fill.id')
            ->where('questions.exam', $auth->exam->id)
            ->get()
            ->reduce(function($sds, $sd) {
                $sds[$sd->id.'-'.$sd->order] = $sd->answer;
                return $sds;
            }, []);

        $oldAnswers =  Answers::select('answer_blank_fill.*', 'answers.question')
            ->join('answer_blank_fill', 'answers.id', '=', 'answer_blank_fill.id')
            ->where('student', $auth->student->id)
            ->get()
            ->reduce(function($as, $a) {
                $as[$a->question . '-' . $a->order] = $a->answer;
                return $as;
            }, []);

        foreach ($questions as $q) {
            $answers = $request->input($q->id);

            if (is_null($answers)) $answers = [];
            $blanks = count($answers);

            $answersObject = Answers::where('student', $auth->student->id)
                ->where('question', $q->id)
                ->first();
            if (is_null($answersObject)) {
                $answersObject = new Answers;
                $answersObject->student = $auth->student->id;
                $answersObject->question = $q->id;
                $answersObject->type = $this->type;
                $answersObject->save();
            }
            $answersObject->submit = Carbon::now();
            $scores = 0;

            for ($i = 0; $i < $blanks; $i++) {
                $key = $q->id.'-'.$i;
                $oldAnswer = isset($oldAnswers[$key]) ? $oldAnswers[$key] : null;
                $standardAnswer = isset($standards[$key]) ? $standards[$key] : null;
                $regexpAnswer = regexp($standardAnswer);
                if (($answer = $answers[$i]) || $oldAnswer) {
                    if ($answer === $oldAnswer) continue;
                    $answerObject = Answer::firstOrNew([
                        'id' => $answersObject->id,
                        'order' => $i,
                    ]);

                    if ($regexpAnswer && preg_match($regexpAnswer, $answer)) {
                        $scores += $q->score;
                    } else if ($standardAnswer === $answer) {
                        $scores += $q->score;
                    }

                    $answerObject->answer = $answer;
                    $answerObject->save();
                }
            }
            $answersObject->score = $scores;
            $answersObject->save();
        }

        return back();
    }
}
