<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramLimit as Limit;
use App\Commands\DeleteProgramQuestion;

class ProgramQuestion extends Controller
{
    protected $type = 'program';

    public function index(Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id);
        }

        $questions = Question::where('exam', $exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.exam.program.index', compact('auth', 'exam', 'questions'));
    }

    public function get(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/program');
        }

        $program = QuestionProgram::findOrFail($question->ref);
        $question->limits = Limit::where('id', $question->ref)->get();

        return view('admin.exam.program.edit', compact('auth', 'exam', 'question', 'program'));
    }

    public function delete(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/program');
        }

        $this->dispatch(new DeleteProgramQuestion($question));

        return redirect('/admin/exam/'.$exam->id.'/program');
    }
}
