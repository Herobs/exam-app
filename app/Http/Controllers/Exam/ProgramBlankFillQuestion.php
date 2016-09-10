<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\AnswerProgramBlankFill as Answer;

/**
 * Exam blank-fill question
 */
class ProgramBlankFillQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'program-blank-fill';

    // show all true-false questions
    public function show(ExamAuth $auth)
    {
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        $answers = Answer::where('student', $auth->student->id)->get();

        return view('exam.blank-fill', [
            'active' => 'blank-fill',
            'auth' => $auth,
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }
    // save answer
    public function save(ExamAuth $auth, Request $request)
    {
        if ($auth->ended) return back();
        $questions = Question::select('id', 'score')
            ->where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->get();

        $oldAnswers = Answer::where('student', $auth->student->id)->get();

        return back();
    }
}
