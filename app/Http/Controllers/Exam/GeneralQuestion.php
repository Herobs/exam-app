<?php

namespace App\Http\Controllers\Exam;

use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use ExamAuth;
use App\Models\Question;
use App\Models\AnswerGeneral as Answer;
use App\Models\Answer as Answers;

/**
 * Exam general question
 */
class GeneralQuestion extends Controller
{
    /**
     * Question type
     */
    protected $type = 'general';

    // show all general questions
    public function show(ExamAuth $auth)
    {
        if ($auth->pending) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->orderBy('id', 'asc')
            ->get();

        $answers = Answers::select('answer_general.*', 'answers.question')
            ->join('answer_general', 'answers.id', '=', 'answer_general.id')
            ->where('student', $auth->student->id)
            ->get()
            ->keyBy('question');

        return view('exam.general', [
            'active' => $this->type,
            'auth' => $auth,
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }
    // save answer
    public function save(ExamAuth $auth, Request $request)
    {
        if (!$auth->running) return redirect('/exam/'.$auth->exam->id);
        $questions = Question::where('exam', $auth->exam->id)
            ->where('type', $this->type)
            ->pluck('id');

        $errors = array();
        foreach ($questions as $q) {
            $file = $request->file($q);
            if ($file->isValid()) {
                $answersObject = Answers::where('student', $auth->student->id)
                    ->where('question', $q)
                    ->first();
                if (is_null($answersObject)) {
                    $answersObject = new Answers;
                    $answersObject->student = $auth->student->id;
                    $answersObject->question = $q;
                    $answersObject->type = $this->type;
                    $answersObject->save();

                    $answerObject = new Answer;
                    $answerObject->id = $answersObject->id;
                } else {
                    $answerObject = Answer::find($answersObject->id);
                }
                $answersObject->submit = Carbon::now();
                $answersObject->score = null;
                $answersObject->save();

                $answerObject->answer = $file->getClientOriginalName();
                $answerObject->save();
                // move file
                $file->move(storage_path('files'), $q);
            } else {
                $errors['file'.$q] = '上传失败，可能是不支持的文件类型，或者文件体积过大。';
            }
        }

        return back()->withErrors();
    }
}
