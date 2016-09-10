<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdminAuth as Auth;
use App\Models\Teacher as TeacherModel;

class Teacher extends Controller
{
    public function index(Request $request, Auth $auth)
    {
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_ADMIN'))) {
            return redirect('/admin');
        }
        if ($keywords = $request->input('keywords')) {
            $teachers = TeacherModel::where('name', 'LIKE', "%$keywords%")
                ->orWhere('email', 'LIKE', "%$keywords%")
                ->paginate(config('system.admin_teacher_list_paginate'));
            $teachers->appends(['keywords' => $keywords]);
        } else {
            $teachers = TeacherModel::paginate(config('system.admin_teacher_list_paginate'));
        }

        return view('admin.teacher.index', compact('auth', 'teachers'));
    }

    public function create(Auth $auth)
    {
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_ADMIN'))) {
            return redirect('/admin');
        }
        return view('admin.teacher.new', compact('auth'));
    }

    public function get(Auth $auth, TeacherModel $teacher)
    {
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_ADMIN'))) {
            return redirect('/admin');
        }
        return view('admin.teacher.edit', compact('auth', 'teacher'));
    }

    public function save(Request $request, Auth $auth)
    {
        if (!hasRight($auth->admin->rights, config('rights.RIGHT_ADMIN'))) {
            return redirect('/admin');
        }
        $this->validate($request, [
            'id' => 'integer',
            'name' => 'required|max:16',
            'email' => 'required|email|max:32|unique:teachers,email'.($request->has('id') ? ','.$request->input('id') : ''),
            'tel' => 'required|max:16',
            'rights.*' => 'required|accepted',
            'password' => 'required_without:id|min:6',
        ], [
            'email.unique' => '该邮箱已经被使用。',
        ]);
        if ($request->has('id')) {
            $teacher = TeacherModel::find($request->input('id'));
        } else {
            $teacher = new TeacherModel;
        }
        $teacher->name = $request->input('name');
        $teacher->email = $request->input('email');
        $teacher->tel = $request->input('tel');
        $teacher->rights = 0;
        foreach ($request->input('rights') ?: [] as $right => $value) {
            $teacher->rights |= 1 << $right;
        }
        if ($request->has('password')) {
            $teacher->password = bcrypt($request->input('password'));
        }
        $teacher->save();

        return redirect('/admin/teachers');
    }
}
