<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;

/**
 * Exam general question
 */
class GeneralQuestion extends Controller
{
    public function show(ExamAuth $auth)
    {
        return view('exam.true-false', [
            'active' => 'general',
            'auth' => $auth,
        ]);
    }
}
