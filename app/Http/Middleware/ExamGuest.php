<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Exam;

class ExamGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exam = $request->route('exam');

        if ($exam->type === 'password' && Auth::guard('user')->guest()) {
            return redirect()->guest('/login');
        }

        $student = session($exam->getSessionKey());
        if (!is_null($student)) {
            return redirect('/exam/'.$exam->id);
        }

        return $next($request);
    }
}
