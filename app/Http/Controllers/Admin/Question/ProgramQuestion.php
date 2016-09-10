<?php

namespace App\Http\Controllers\Admin\Question;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramLimit as Limit;
use App\Commands\DeleteProgramQuestion;

class ProgramQuestion extends Controller
{
    protected $type = 'program';
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function get(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => $this->type,
            'question' => '程序设计题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/'.$meta->type);
        }

        $program = QuestionProgram::findOrFail($question->ref);
        $question->limits = Limit::where('id', $question->ref)->get();

        return view('admin.question.program.edit', compact('auth', 'question', 'program', 'meta'));
    }

    public function delete(Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/program');
        }

        $this->dispatch(new DeleteProgramQuestion($question));

        return redirect('/admin/'.$category.'/program');
    }
}
