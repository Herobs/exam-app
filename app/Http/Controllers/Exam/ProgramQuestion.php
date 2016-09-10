<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\ProgramLimit;
use App\Models\AnswerProgram as Answer;
use App\Models\Answer as Answers;

/**
 * Exam program question
 */
class ProgramQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'program';

    // redirect to the first program question
    public function index(ExamAuth $auth, Request $request)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        $firstQuestion = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->value('id');
        return redirect('/exam/'.$auth->exam->id.'/program/'.$firstQuestion);
    }
    // show specific program question
    public function show(ExamAuth $auth, Request $request)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::select('id', 'score')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        $answers = Answers::select('answer_program.*', 'answers.question')
            ->join('answer_program', 'answers.id', '=', 'answer_program.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');
        foreach ($questions as &$q) {
            if (isset($answers[$q->id])) {
                if ($auth->ended) {
                    if ($answers[$q->id]->score > 0) {
                        $q->status = 'accepted';
                    } else {
                        $q->status = 'wrong';
                    }
                } else {
                    $q->status = 'touched';
                }
            } else {
                $q->status = 'untouched';
            }
        }

        $questionId = intval($request->route('question'));
        $question = Question::join('question_program', 'questions.id', '=', 'question_program.id')
            ->findOrFail($questionId);

        $answer = Answers::select('answer_program.*', 'answers.question')
            ->join('answer_program', 'answers.id', '=', 'answer_program.id')
            ->where('student', $auth->student->id)
            ->where('question', $questionId)
            ->first();
        if (!is_null($answer)) $question->answer = $answer->answer;

        //$limits = ProgramLimit::where('id', $question->id);

        return view('exam.program', [
            'active' => $this->type,
            'auth' => $auth,
            'questions' => $questions,
            'question' => $question,
        ]);
    }
    // save answer
    public function save(ExamAuth $auth, Request $request)
    {
        if (!$auth->running) return redirect('/exam/'.$auth->exam->id);
        $question = Question::find($request->route('question'));
        if ($question->exam === $auth->exam->id) {
            $answer = $request->input('code');
            $language = $request->input('language');
            if ($answer) {
                $answersObject = Answers::where('student', $auth->student->id)
                    ->where('question', $question->id)
                    ->first();
                if (is_null($answersObject)) {
                    $answersObject = new Answers;
                    $answersObject->student = $auth->student->id;
                    $answersObject->question = $question->id;
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
                $answerObject->language = $language;
                $answerObject->save();
            }
        }

        return back();
    }
}
