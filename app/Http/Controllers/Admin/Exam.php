<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam as ExamModel;

class Exam extends controller
{
    public function index(Request $request, Auth $auth)
    {
        $exams = ExamModel::where('holder', $auth->admin->id)
            ->orderBy('start', 'desc')
            ->paginate(15);

        return view('admin.exams', [
            'auth' => $auth,
            'exams' => $exams,
        ]);
    }

    public function get(Auth $auth, ExamModel $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exams');
        }
        return view('admin.exam.edit', [
            'auth' => $auth,
            'exam' => $exam,
        ]);
    }

    public function create(Auth $auth)
    {
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_EXAM'))) {
            return redirect('/admin');
        }
        return view('admin.exam.new', [
            'auth' => $auth,
        ]);
    }

    public function save(Request $request, Auth $auth)
    {
        $this->validate($request, [
            'name' => 'required|max:64',
            'start' => 'required|date',
            'hours' => 'required|integer',
            'minutes' => 'required|integer',
            'type' => 'required|in:student,account,password',
            'password' => 'required_if:type,password',
            'hidden' => 'required|in:true,false',
        ]);


        if ($request->has('id')) {
            $exam = ExamModel::findOrFail($request->input('id'));
            if ($auth->admin->cannot('update', $exam)) {
                return redirect('/admin/exams');
            }
        } else {
            if (!hasRight($auth->admin->rights, config('rights.RIGHT_EXAM'))) {
                return redirect('/admin/exams');
            }
            $exam = new ExamModel;
            $exam->holder = $auth->admin->id;
        }
        $exam->name = $request->input('name');
        $exam->start = Carbon::createFromTimestamp(strtotime($request->input('start')));
        $exam->duration = intval($request->input('hours')) * 3600 + intval($request->input('minutes')) * 60;
        $exam->type = $request->input('type');
        if ($exam->type === 'password') $exam->password = $request->input('password');
        $exam->hidden = $request->input('hidden');
        $exam->save();

        if ($request->has('id')) {
            return back();
        } else {
            return redirect('/admin/exams');
        }
    }

    // not a good idea for deleting item with GET method
    public function delete(Auth $auth, ExamModel $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exams');
        }
        // delete questions
        \DB::delete('DELETE questions, question_multi_choice, question_program,' .
            ' program_limits, program_files,' .
            ' standard_true_false, standard_multi_choice, standard_blank_fill' .
            ' FROM questions LEFT JOIN question_multi_choice ON questions.id = question_multi_choice.id' .
            ' LEFT JOIN question_program ON questions.id = question_program.id' .
            ' LEFT JOIN program_limits ON question_program.id = program_limits.id' .
            ' LEFT JOIN program_files ON question_program.id = program_files.id' .
            ' LEFT JOIN standard_true_false ON questions.id = standard_true_false.id' .
            ' LEFT JOIN standard_multi_choice ON questions.id = standard_multi_choice.id' .
            ' LEFT JOIN standard_blank_fill ON questions.id = standard_blank_fill.id' .
            ' WHERE questions.exam = ?'
        , [$exam->id]);
        // delete students and answers
        \DB::delete('DELETE students, answers, answer_true_false, answer_multi_choice, answer_blank_fill, answer_short_answer, answer_general, answer_program' .
            ' FROM students LEFT JOIN answers ON students.id = answers.student' .
            ' LEFT JOIN answer_true_false ON answers.id = answer_true_false.id' .
            ' LEFT JOIN answer_multi_choice ON answers.id = answer_multi_choice.id' .
            ' LEFT JOIN answer_blank_fill ON answers.id = answer_blank_fill.id' .
            ' LEFT JOIN answer_short_answer ON answers.id = answer_short_answer.id' .
            ' LEFT JOIN answer_general ON answers.id = answer_general.id' .
            ' LEFT JOIN answer_program ON answers.id = answer_program.id' .
            ' WHERE students.exam = ?'
        , [$exam->id]);
        // delete exam
        $exam->delete();
        return redirect('/admin/exams');
    }
}
