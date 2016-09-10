<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use AdminAuth as Auth;
use App\Models\Exam;

class Admin extends Controller
{
    public function index(Auth $auth)
    {
        $exams = Exam::where('holder', $auth->admin->id)
            ->orderBy('start', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact('auth', 'exams'));
    }

    public function get(Auth $auth)
    {
        return view('admin.edit', compact('auth'));
    }

    public function save(Request $request, Auth $auth)
    {
        $this->validate($request, [
            'name' => 'required|max:16',
            'tel' => 'required|max:16',
            'newpass' => 'min:6|confirmed',
            'password' => 'required',
        ]);
        if (Hash::check($request->input('password'), $auth->admin->password)) {
            $auth->admin->name = $request->input('name');
            $auth->admin->tel = $request->input('tel');
            if ($request->has('newpass')) {
                $auth->admin->password = bcrypt($request->input('newpass'));
            }
            $auth->admin->save();
            return redirect('/admin');
        } else {
            return back()
                ->withInput($request->all())
                ->withErrors(['password' => '密码错误。']);
        }
    }
}
