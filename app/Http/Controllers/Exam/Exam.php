<?php

namespace App\Http\Controllers\Exam;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Exam as ExamModel;
use App\Models\Question;
use App\Models\Answer;

class Exam extends Controller
{
    public function getIndex(ExamAuth $auth)
    {
        if ($auth->pending) {
            return view('exam.index', [
                'auth' => $auth,
                'active' => 'index',
            ]);
        }

        $answers = Answer::select('answers.*')
            ->join('students', 'students.id', '=', 'answers.student')
            ->where('students.exam', $auth->exam->id)
            ->get()
            ->reduce(function($as, $a) {
                $as[$a->type][$a->question] = $a;
                return $as;
            }, []);

        $questions = Question::select('id', 'type', 'score')
            ->where('exam', $auth->exam->id)
            ->orderBy('id', 'asc')
            ->get()
            ->reduce(function($qs, $q) use (&$answers, &$auth) {
                if (isset($answers[$q->type][$q->id])) {
                    $q->scoreGet = $answers[$q->type][$q->id]->score;
                    if ($auth->ended && $q->scoreGet !== null) {
                        if ($q->scoreGet > 0) {
                            $q->status = 'accepted';
                        } else {
                            $q->status = 'wrong';
                        }
                    } else {
                        $q->status = 'touched';
                    }
                } else {
                    $q->scoreGet = 0;
                    $q->status = 'untouched';
                }
                $qs[$q->type][] = $q;
                return $qs;
            }, []);

        return view('exam.index', [
            'active' => 'index',
            'auth' => $auth,
            'questions' => $questions,
        ]);
    }

    public function getExams(Request $request, $type = 'all')
    {
        $exams = ExamModel::where('hidden', 0)
            ->join('teachers', 'exams.holder', '=', 'teachers.id')
            ->select('exams.id', 'exams.name', 'start', 'duration', 'teachers.name as teacher');

        if ($type === 'pending') {
            $exams = $exams->whereRaw("start > NOW()");
        } else if ($type === 'running') {
            $exams = $exams->whereRaw('start <= NOW()')
                ->whereRaw('ADDTIME(start, SEC_TO_TIME(duration)) >= NOW()');
        } else if ($type === 'ended') {
            $exams = $exams->whereRaw('ADDTIME(start, SEC_TO_TIME(duration)) < NOW()');
        }

        if ($keywords = $request->input('keywords')) {
            $exams = $exams->where('exams.name', 'LIKE', "%$keywords%");
        }

        $exams = $exams->orderBy('start', 'desc')->paginate(12);
        if ($keywords) {
            $exams->appends(['keywords' => $keywords]);
        }

        return view('exam.list', [
            'type' => $type,
            'keywords' => $keywords,
            'exams' => $exams,
        ]);
    }
}
