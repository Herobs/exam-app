<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;
use App\Models\StandardTrueFalse as Standard;

class TrueFalseQuestion extends Controller
{
    protected $type = 'true-false';

    public function get(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        $question->answer = Standard::where('id', $question->ref)->value('answer');

        return view('admin.exam.true-false.edit', compact('auth', 'exam', 'question'));
    }

    public function delete(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        \DB::delete('DELETE questions, standard_true_false' .
            ' FROM questions LEFT JOIN standard_true_false ON questions.id = standard_true_false.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
    }
}
