<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Question;
use Carbon\Carbon;

class ExamAuthenticate
{
    // exam object
    public $exam;
    // student object
    public $student;
    // exam status
    public $pending = false;
    public $running = false;
    public $ended = false;
    // exam questions
    public $questions;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exam = Exam::findOrFail($request->route('exam'));
        $student = session($exam->getSessionKey());
        // student id limited, global account login required
        if ($exam->type === 'student') {
            if (Auth::guard('user')->guest()) {
                return redirect()->guest('login');
            }

            if (is_null($student)) {
                $user = Auth::user();
                if ($student = Student::where('exam', $exam->id)
                    ->where('student', $user->student)
                    ->first()
                ) { // current student allow in this exam
                    // save information from user
                    $student->name = $user->name;
                    $student->major = $user->major;
                    $student->last = Carbon::now();
                    $student->ip = $request->ip();
                    $student->save();

                    // store in session
                    session([
                        $exam->getSessionKey() => (object)$student->toArray()
                    ]);
                } else {
                    return redirect('/exam/' . $exam->id . '/forbidden');
                }
            }
        } else if ($exam->type === 'account') {
            if (is_null($student) || $student->exam !== $exam->id) {
                return redirect()->guest('/exam/' . $exam->id . '/login');
            }
        } else if ($exam->type === 'password') {
            if (Auth::guard('user')->guest() || $student === null ||
                $student->exam !== $exam->id) {
                return redirect()->guest('/exam/' . $exam->id . '/login');
            }
        }

        // exam infomation
        $this->exam = $exam;
        // current login student
        $this->student = $student;
        // exam status
        if ($exam->start > Carbon::now()) {
            $this->pending = true;
        } else if ($exam->start->addSeconds($exam->duration) < Carbon::now()) {
            $this->ended = true;
        } else {
            $this->running = true;
        }

        // exam questions
        $questions = Question::select('type')
            ->where('exam', $exam->id)
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        $this->questions = $questions;

        return $next($request);
    }
}
