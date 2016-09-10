<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question as QuestionModel;

class Question extends Controller
{
    protected $types = ['public' => '公共题库', 'private' => '我的题库'];
    protected $questionTypes = [
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

    public function redirect(Request $request)
    {
        return redirect('/admin/'.$request->route('category').'/true-false');
    }

    public function index(Request $request, Auth $auth, $category, $type)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->types[$category],
            'type' => $type,
            'question' => $this->questionTypes[$type],
        ];

        $admin = $auth->admin->id;
        if ($meta->category === 'public') $admin = 0;

        $questions = QuestionModel::from(DB::raw('questions FORCE INDEX (questions_user_type_index)'))
            ->where('user', $admin)
            ->where('type', $meta->type)
            ->where('exam', 0)
            ->paginate(config('system.admin_exam_import_paginate'));

        if ($type === 'program') {
            return view('admin.question.program.index', compact('auth', 'meta', 'questions'));
        } else {            
            return view('admin.question.index', compact('auth', 'meta', 'questions'));
        }
    }

    public function create(Request $request, Auth $auth, Exam $exam)
    {
        $meta = (object)[
            'category' => $request->route('category'),
            'title' => $this->types[$request->route('category')],
            'type' => $request->route('type'),
            'question' => $this->questionTypes[$request->route('type')],
        ];
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_QUESTION'))) {
            return redirect('/'.$meta->category);
        }
        return view('admin.question.'.$request->route('type').'.new', compact('auth', 'meta'));
    }

    public function save(Request $request, Auth $auth)
    {
        $meta = (object)[
            'category' => $request->route('category'),
            'type' => $request->route('type'),
        ];

        $admin = $auth->admin->id;
        if ($meta->category === 'public') $admin = 0;

        $command = $this->commands[$meta->type];
        $this->dispatch(new $command($request, $admin, 0, $auth->admin->rights));

        return redirect('/admin/'.$meta->category.'/'.$meta->type);
    }
}
