<?php

namespace App\Http\Controllers\Admin\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question as QuestionModel;

class Question extends Controller
{
    protected $types = [
        'true-false' => '判断题',
        'multi-choice' => '选择题',
        'blank-fill' => '填空题',
        'short-answer' => '简答题',
        'general' => '综合题',
        'program' => '编程题',
    ];
    protected $commands = [
        'true-false' => \App\Commands\SaveTrueFalseQuestion::class,
        'multi-choice' => \App\Commands\SaveMultiChoiceQuestion::class,
        'blank-fill' => \App\Commands\SaveBlankFillQuestion::class,
        'short-answer' => \App\Commands\SaveShortAnswerQuestion::class,
        'general' => \App\Commands\SaveGeneralQuestion::class,
        'program' => \App\Commands\SaveProgramQuestion::class,
    ];

    public function index(Request $request, Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id);
        }

        $meta = (object)[
            'type' => $request->route('type'),
            'question' => $this->types[$request->route('type')],
        ];

        $questions = QuestionModel::where('exam', $exam->id)
            ->where('type', $meta->type)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.exam.questions', compact('auth', 'exam', 'questions', 'meta'));
    }

    public function create(Request $request, Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam) || !hasRight($auth->admin->rights, config('rights.RIGHT_QUESTION'))) {
            return redirect('/admin/exam/'.$exam->id.$request->route('type'));
        }
        return view('admin.exam.'.$request->route('type').'.new', compact('auth', 'exam'));
    }

    public function save(Request $request, Auth $auth, Exam $exam)
    {
        $type = $request->route('type');

        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$type);
        }

        $command = $this->commands[$type];
        $this->dispatch(new $command($request, $auth->admin->id, $exam->id, $auth->admin->rights));

        return redirect('/admin/exam/'.$exam->id.'/'.$type);
    }
}
