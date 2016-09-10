<?php

namespace App\Http\Controllers\Admin\Question;

use Judge\Judge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramFile as File;
use App\Commands\SaveTestCase;
use App\Commands\DeleteTestCase;

class TestCase extends Controller
{
    protected $categories = ['public' => '公共题库', 'private' => '我的题库'];

    public function index(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => 'program',
            'question' => '程序设计题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/program');
        }

        $question->files = File::where('id', $question->ref)
            ->groupBy('case')
            ->get()
            ->reduce(function($files, $file) {
                $files[$file->case.'-'.$file->type] = $file;
                return $files;
            }, []);

        $program = QuestionProgram::findOrFail($question->ref);

        $messages = array();
        for ($i = 1; $i <= $program->test_case; $i++) {
            if (!isset($question->files[$i.'-input'])) {
                $messages[] = ' 缺少第 '.$i.' 组测试文件';
            }
        }

        return view('admin.question.program.testcase.index', compact('auth', 'question', 'messages', 'meta'));
    }

    public function create(Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => 'program',
            'question' => '程序设计题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/program/'.$question->id.'/testcase');
        }

        return view('admin.question.program.testcase.new', compact('auth', 'question', 'meta'));
    }

    public function get(Request $request, Auth $auth, $category, Question $question)
    {
        $meta = (object)[
            'category' => $category,
            'title' => $this->categories[$category],
            'type' => 'program',
            'question' => '程序设计题',
        ];

        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$meta->category.'/program/'.$question->id.'/testcase');
        }

        $question->case = $request->route('testcase');
        File::where('id', $question->ref)
            ->where('case', $question->case)
            ->get()
            ->each(function($file) use(&$question) {
                $question->{$file->type} = $file->content;
            });

        return view('admin.question.program.testcase.edit', compact('auth', 'question', 'meta'));
    }

    public function save(Request $request, Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/program/'.$question->id.'/testcase');
        }

        $this->dispatch(new SaveTestCase($request, $question));

        return redirect('/admin/'.$category.'/program/'.$question->id.'/testcase');
    }

    // not a good idea for deleting item with GET method
    public function delete(Request $request, Auth $auth, $category, Question $question)
    {
        if ($auth->admin->cannot('update', $question)) {
            return redirect('/admin/'.$category.'/program/'.$question->id.'/testcase');
        }

        $this->dispatch(new DeleteTestCase($request, $question));

        return redirect('/admin/'.$category.'/program/'.$question->id.'/testcase');
    }
}
