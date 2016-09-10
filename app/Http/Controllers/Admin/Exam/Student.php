<?php

namespace App\Http\Controllers\Admin\Exam;

use Excel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthenticate as Auth;
use App\Models\Exam;
use App\Models\Student as StudentModel;

class ValueBinder extends \PHPExcel_Cell_DefaultValueBinder implements \PHPExcel_Cell_IValueBinder
{
    public function bindValue(\PHPExcel_Cell $cell, $value = null)
    {
        $cell->setValueExplicit($value, \PHPExcel_Cell_DataType::TYPE_STRING);
        return true;
    }
}

class Student extends controller
{
    public function index(Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id);
        }
        $students = StudentModel::where('exam', $exam->id)->get();

        return view('admin.exam.student.index', [
            'auth' => $auth,
            'exam' => $exam,
            'students' => $students,
        ]);
    }

    public function get(Request $request, Auth $auth, Exam $exam, StudentModel $student)
    {
        if ($auth->admin->cannot('update', $exam) || $exam->type !== 'account') {
            return redirect('/admin/exam/'.$exam->id);
        }
        return view('admin.exam.student.edit', [
            'auth' => $auth,
            'exam' => $exam,
            'student' => $student,
        ]);
    }

    public function save(Request $request, Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id);
        }

        if ($exam->type === 'student') {
            $this->validate($request, [
                'student' => 'required|max:16',
            ]);

        } else if ($exam->type === 'account') {
            $this->validate($request, [
                'name' => 'required|max:16',
                'student' => 'required|max:16',
                'major' => 'max:32',
                'password' => 'required_without:id|min:6',
            ]);
        } else {
            return redirect('/admin/exam/'.$exam->id);
        }
        if (!is_null(StudentModel::where('exam', $exam->id)->where('student', $request->input('student'))->first())) {
            return back()
                ->withInput($request->except('_token'))
                ->withErrors([
                    'student' => '本场考试中已存在该学号。'
                ]);
        }

        if ($request->has('id')) {
            $student = StudentModel::findOrFail($request->input('id'));
            if ($student->exam !== $exam->id) {
                return redirect('/admin/exam/'.$exam->id.'/student');
            }
        } else {
            $student = new StudentModel;
            $student->exam = $exam->id;
        }

        if ($request->has('name')) $student->name = $request->input('name');
        $student->student = $request->input('student');
        $student->major = $request->input('major');
        if ($request->input('password') !== '') $student->password = bcrypt($request->input('password'));
        $student->save();

        return redirect('/admin/exam/'.$exam->id.'/student');
    }

    public function create(Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exam/'.$exam->id);
        }
        return view('admin.exam.student.new', [
            'auth' => $auth,
            'exam' => $exam,
        ]);
    }

    // not a good idea for deleting item with GET method
    public function delete(Auth $auth, Exam $exam, StudentModel $student)
    {
        if ($auth->admin->cannot('update', $exam) || $student->exam !== $exam->id) {
            return redirect('/admin/exam/'.$exam->id);
        }
        // delete answers
        \DB::delete('DELETE answers, answer_true_false, answer_multi_choice, answer_blank_fill, answer_short_answer, answer_general, answer_program' .
            ' FROM answers LEFT JOIN answer_true_false ON answers.id = answer_true_false.id' .
            ' LEFT JOIN answer_multi_choice ON answers.id = answer_multi_choice.id' .
            ' LEFT JOIN answer_blank_fill ON answers.id = answer_blank_fill.id' .
            ' LEFT JOIN answer_short_answer ON answers.id = answer_short_answer.id' .
            ' LEFT JOIN answer_general ON answers.id = answer_general.id' .
            ' LEFT JOIN answer_program ON answers.id = answer_program.id' .
            ' WHERE answers.student = ?'
        , [$student->id]);
        // delete student
        $student->delete();

        return redirect('/admin/exam/'.$exam->id.'/student');
    }

    // not a good idea for deleting item with GET method
    public function clear(Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exams');
        }
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
        return redirect('/admin/exam/'.$exam->id.'/student');
    }

    public function importForm(Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exams');
        }
        return view('admin.exam.student.import', [
            'auth' => $auth,
            'exam' => $exam,
        ]);
    }

    public function import(Request $request, Auth $auth, Exam $exam)
    {
        if ($auth->admin->cannot('update', $exam)) {
            return redirect('/admin/exams');
        }
        $this->validate($request, [
            'students' => 'required|mimes:txt,csv,xls,xlsx',
        ]);
        $studentsFile = $request->file('students');
        $studentsPath = 'students_import_'.$exam->id.'.'.$studentsFile->getClientOriginalExtension();
        $studentsFile->move(storage_path('temp'), $studentsPath);
        $studentsPath = storage_path('temp') . '/' . $studentsPath;

        $errors = array();
        $valueBinder = new ValueBinder;
        $students = array();
        Excel::setValueBinder($valueBinder)->load($studentsPath)
            ->all()
            ->each(function($student) use (&$exam, &$students) {
                if (is_null($student->student)) return true;
                if (!is_null(StudentModel::where('exam', $exam->id)->where('student', $student->student)->first())) return true;
                $student->student = mb_substr($student->student, 0, 16);
                if ($exam->type === 'account') {
                    if (is_null($student->name)) return true;
                    $student->name = mb_substr($student->name, 0, 16);
                    if (is_null($student->password)) return true;
                    $student->password = bcrypt($student->password);
                    $student->major = mb_substr($student->major, 0, 32);
                } else {
                    $student->name = null;
                    $student->major = null;
                    $student->password = null;
                }
                $students[] = [
                    'exam' => $exam->id,
                    'name' => $student->name,
                    'student' => $student->student,
                    'major' => $student->major,
                    'password' => $student->password,
                ];
            });
        \DB::table('students')->insert($students);

        unlink($studentsPath);

        return redirect('/admin/exam/'.$exam->id.'/student');
    }

    public function export(Auth $auth, Exam $exam)
    {

    }
}
