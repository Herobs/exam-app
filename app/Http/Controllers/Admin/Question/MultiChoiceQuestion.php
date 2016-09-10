<?php

namespace App\Http\Controllers\Admin\Question;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;
use App\Models\StandardMultiChoice as Standard;
use App\Models\QuestionMultiChoice;

class MultiChoiceQuestion extends Controller
{
    protected $type = 'multi-choice';
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function get(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => $this->type,
            'question' => '选择题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/'.$meta->type);
        }

        $question->answer = Standard::where('id', $question->ref)->value('answer');
        $question->options = QuestionMultiChoice::where('id', $question->ref)->get();

        return view('admin.question.multi-choice.edit', compact('auth', 'question', 'meta'));
    }

    public function delete(Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/'.$this->type);
        }

        \DB::delete('DELETE questions, question_multi_choice, standard_multi_choice' .
            ' FROM questions' .
            ' LEFT JOIN question_multi_choice ON questions.id = question_multi_choice.id' .
            ' LEFT JOIN standard_multi_choice ON questions.id = standard_multi_choice.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/'.$category.'/'.$this->type);
    }
}
