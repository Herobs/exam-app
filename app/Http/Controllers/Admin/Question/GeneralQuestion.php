<?php

namespace App\Http\Controllers\Admin\Question;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;

class GeneralQuestion extends Controller
{
    protected $type = 'general';
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function get(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => $this->type,
            'question' => '综合题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/'.$meta->type);
        }

        return view('admin.question.general.edit', compact('auth', 'question', 'meta'));
    }

    public function delete(Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/'.$this->type);
        }

        \DB::delete('DELETE FROM questions WHERE questions.id = ?', [$question->id]);

        return redirect('/admin/'.$category.'/'.$this->type);
    }
}
