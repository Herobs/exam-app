<?php

namespace App\Http\Controllers\Exam;

use Hash;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Models\Exam;
use App\Models\Teacher;
use App\Models\Student;

class AuthController extends Controller
{
    use ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.exam', ['except' => [
            'getLogout',
        ]]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return 'student';
    }

    // show login form
    public function getLogin(Exam $exam) {
        return view('exam.login', [
            'exam' => $exam,
            'teacher' => Teacher::find($exam->holder)
        ]);
    }

    // process import accounts and public password login
    public function postLogin(Request $request, Exam $exam) {
        if ($exam->type === 'account') {
            $this->validate($request, [
                'student' => 'required|max:16',
                'password' => 'required',
            ]);
            if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }
            $student = Student::where('exam', $exam->id)
                ->where('student', $request->input('student'))
                ->first();
            if ($student && Hash::check($request->input('password'), $student->password)) {
                $student->last = \Carbon\Carbon::now();
                $student->ip = $request->ip();
                $student->save();

                // store in session
                session([
                    $exam->getSessionKey() => (object)$student->toArray()
                ]);

                $this->clearLoginAttempts($request);

                return redirect('/exam/' . $exam->id);
            }
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if (!$lockedOut) {
                $this->incrementLoginAttempts($request);
            }
            return back()
                ->withInput(['student' => $request->input('student')])
                ->withErrors(['student' => trans('exam.auth.failed')]);
        } else if ($exam->type === 'password') {
            $this->validate($request, [
                'password' => 'required',
            ]);
            if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                $seconds = $this->secondsRemainingOnLockout($request);
                return redirect()->back()
                    ->withErrors([
                        'password' => $this->getLockoutErrorMessage($seconds),
                ]);
            }
            if ($request->input('password') === $exam->password) {
                $user = $request->user();
                $student = Student::where('exam', $exam->id)
                    ->where('student', $user->student)
                    ->first();
                if (is_null($student)) {
                    // first login, insert new record
                    $student = new Student;
                    $student->exam = $exam->id;
                    $student->student = $user->student;
                    $student->name = $user->name;
                    $student->major = $user->major;
                }
                $student->last = \Carbon\Carbon::now();
                $student->ip = $request->ip();
                $student->save();

                // store in session
                session([
                    $exam->getSessionKey() => (object)$student->toArray()
                ]);

                $this->clearLoginAttempts($request);

                return redirect('/exam/' . $exam->id);
            }
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if (!$lockedOut) {
                $this->incrementLoginAttempts($request);
            }
            return back()
                ->withErrors(['password' => trans('exam.auth.password')]);
        }
    }

    // exam logout
    public function getLogout(Exam $exam)
    {
        session()->forget($exam->getSessionKey());
        return redirect('/exams');
    }

    // exam forbidden
    public function getForbidden(Exam $exam)
    {
        return view('exam.forbidden', [
            'exam' => $exam
        ]);
    }
}
