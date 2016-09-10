<?php

namespace App\Http\Controllers\Admin\Question;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;
use App\Models\StandardBlankFill as Standard;

class BlankFillQuestion extends Controller
{
    protected $type = 'blank-fill';
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function get(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => $this->type,
            'question' => '填空题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/'.$meta->type);
        }

        $question->answers = Standard::where('id', $question->ref)->get();

        return view('admin.question.blank-fill.edit', compact('auth', 'question', 'meta'));
    }

    public function delete(Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/'.$this->type);
        }

        \DB::delete('DELETE questions, standard_blank_fill' .
            ' FROM questions' .
            ' LEFT JOIN standard_blank_fill ON questions.id = standard_blank_fill.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/'.$category.'/'.$this->type);
    }
}
