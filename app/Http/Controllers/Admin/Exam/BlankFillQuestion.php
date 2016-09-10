<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;
use App\Models\StandardBlankFill as Standard;

class BlankFillQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'blank-fill';

    public function get(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        $question->answers = Standard::where('id', $question->ref)->get();

        return view('admin.exam.blank-fill.edit', compact('auth', 'exam', 'question'));
    }

    public function delete(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        \DB::delete('DELETE questions, standard_blank_fill' .
            ' FROM questions' .
            ' LEFT JOIN standard_blank_fill ON questions.id = standard_blank_fill.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
    }
}
