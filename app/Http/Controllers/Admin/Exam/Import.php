<?php

namespace App\Http\Controllers\Admin\Exam;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Question;

class Import extends Controller
{
    protected $types = ['public' => '公共题库选题', 'private' => '我的题库选题'];
    protected $questionTypes = [
        'true-false' => '判断题',
        'multi-choice' => '选择题',
        'blank-fill' => '填空题',
        'short-answer' => '简答题',
        'general' => '综合题',
        'program' => '编程题',
    ];

    public function index(Auth $auth, Exam $exam, $category, $type)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->types[$category],
            'type' => $type,
            'question' => $this->questionTypes[$type],
        ];

        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$meta->type);
        }

        $admin = $auth->admin->id;
        if ($meta->category === 'public') $admin = 0;

        $questions = Question::from(DB::raw('questions FORCE INDEX (questions_user_type_index)'))
            ->where('user', $admin)
            ->where('type', $meta->type)
            ->where('exam', 0)
            ->paginate(config('system.admin_exam_import_paginate'));

        return view('admin.exam.import', compact('auth', 'exam', 'meta', 'questions'));
    }

    public function import(Auth $auth, Exam $exam, $category, $type, Question $question)
    {
        if ($auth->admin->cannot('update', $exam) || ($question->user !== $auth->admin->id && $question->user !== 0)) {
            return redirect('/admin/exam/'.$exam->id.'/'.$type);
        }

        DB::insert('INSERT INTO questions(user, exam, type, description, score, source, ref) VALUE(?, ?, ?, ?, ?, ?, ?)', [
            $auth->admin->id,
            $exam->id,
            $question->type,
            $question->description,
            $question->score,
            $question->source,
            $question->id,
        ]);

        return redirect('/admin/exam/'.$exam->id.'/'.$type);
    }
}
