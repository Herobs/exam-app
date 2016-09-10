<?php

namespace App\Http\Controllers\Admin\Question;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;
use App\Models\StandardTrueFalse as Standard;

class TrueFalseQuestion extends Controller
{
    protected $type = 'true-false';
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function get(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => $this->type,
            'question' => '判断题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/'.$meta->type);
        }

        $question->answer = Standard::where('id', $question->ref)->value('answer');

        return view('admin.question.true-false.edit', compact('auth', 'question', 'meta'));
    }

    public function delete(Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/'.$this->type);
        }

        \DB::delete('DELETE questions, standard_true_false' .
            ' FROM questions LEFT JOIN standard_true_false ON questions.id = standard_true_false.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/'.$category.'/'.$this->type);
    }
}
