<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;
use App\Models\StandardMultiChoice as Standard;
use App\Models\QuestionMultiChoice;

class MultiChoiceQuestion extends Controller
{
    protected $type = 'multi-choice';

    public function get(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        $question->answer = Standard::where('id', $question->ref)->value('answer');
        $question->options = QuestionMultiChoice::where('id', $question->ref)->get();

        return view('admin.exam.multi-choice.edit', compact('auth', 'exam', 'question'));
    }

    public function delete(Auth $auth, Exam $exam, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
        }

        \DB::delete('DELETE questions, question_multi_choice, standard_multi_choice' .
            ' FROM questions' .
            ' LEFT JOIN question_multi_choice ON questions.id = question_multi_choice.id' .
            ' LEFT JOIN standard_multi_choice ON questions.id = standard_multi_choice.id' .
            ' WHERE questions.id = ?'
        , [$question->id]);

        return redirect('/admin/exam/'.$exam->id.'/'.$this->type);
    }
}
