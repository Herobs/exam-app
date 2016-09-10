<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;

class GeneralQuestion extends Controller
{
    protected $type = 'general';

    public function get(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        return view('admin.exam.general.edit', compact('auth', 'exam', 'question'));
    }

    public function delete(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        \DB::delete('DELETE FROM questions WHERE questions.id = ?', [$question->id]);

        return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
    }
}
