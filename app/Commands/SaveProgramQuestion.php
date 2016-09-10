<?php

namespace App\Commands;

use Judge\Judge;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramLimit as Limit;
use App\Models\ProgramFile as File;
use App\Commands\CopyProgramQuestion;

class SaveProgramQuestion extends Command implements SelfHandling
{
    use ValidatesRequests;
    // question type
    protected $type = 'program';
    // http request
    protected $request;
    // exam id
    protected $exam;
    // admin id
    protected $user;
    // admin rights
    protected $rights;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Request $request, $user, $exam, $rights)
    {
        $this->request = $request;
        $this->user = $user;
        $this->exam = $exam;
        $this->rights = $rights;
    }

    /**
     * Execute the command.
     */
    public function handle(Judge $judge)
    {
        $this->validate($this->request, [
            'description' => 'required|max:65535',
            'limits.time.default' => 'required',
            'limits.time.*' => 'integer|max:'.config('judge.max_time_limit'),
            'limits.memory.default' => 'required',
            'limits.time.*' => 'integer|max:'.config('judge.max_time_limit'),
            'outputlimit' => 'required|max:'.config('judge.max_output_limit'),
            //'specialjudge' => 'required|boolean',
            'testcase' => 'required|integer|min:1|max:'.config('judge.max_testcase'),
            'score' => 'required|integer|max:255',
        ]);

        if ($this->request->has('id')) {
            $question = Question::findOrFail($this->request->input('id'));
            if ($question->user !== $this->user) {
                return false;
            }
            if ($question->id !== $question->ref) {
                dispatch(new CopyProgramQuestion($question));
            }
            $program = QuestionProgram::findOrFail($question->id);
        } else {
            if (!hasRight($this->rights, config('rights.RIGHT_PROGRAM'))) {
                return false;
            }
            $question = new Question;
            $question->user = $this->user;
            $question->exam = $this->exam;
            $question->type = $this->type;
            $question->save();
            // set ref to id when create new question
            $question->ref = $question->id;

            $program = new QuestionProgram;
            $program->id = $question->id;
        }
        $question->description = $this->request->input('description');
        $question->score = $this->request->input('score');
        $question->save();

        // time limits
        foreach ($this->request->input('limits.time') as $language => $value) {
            $limit = Limit::firstOrNew([
                'id' => $question->id,
                'type' => 'time',
                'language' => $language,
            ]);
            $limit->value = $value;
            if ($limit->exists) {
                Limit::where('id', $limit->id)
                    ->where('type', $limit->type)
                    ->where('language', $limit->language)
                    ->update(['value' => $limit->value]);
            } else {
                $limit->save();
            }
        }
        // memory limits
        foreach ($this->request->input('limits.memory') as $language => $value) {
            $limit = Limit::firstOrNew([
                'id' => $question->id,
                'type' => 'memory',
                'language' => $language,
            ]);
            $limit->value = $value;
            if ($limit->exists) {
                Limit::where('id', $limit->id)
                    ->where('type', $limit->type)
                    ->where('language', $limit->language)
                    ->update(['value' => $limit->value]);
            } else {
                $limit->save();
            }
        }

        $program->title = $this->request->input('title');
        $program->output_limit = $this->request->input('outputlimit');
        $program->special_judge = false; // $this->request->input('specialjudge');
        $program->test_case = $this->request->input('testcase');

        $newQuestion = [
            'timeLimits' => $this->request->input('limits.time'),
            'memoryLimits' => $this->request->input('limits.memory'),
            'outputLimit' => $this->request->input('outputlimit'),
            'specialJudge' => false,
            'testCase' => $this->request->input('testcase'),
        ];
        if (intval($program->remote) <= 0) {
            $result = $judge->addProblem($newQuestion);
            $program->remote = $result->problemId;
        } else {
            $result = $judge->updateProblem($program->remote, $newQuestion);
        }
        $program->save();

        return true;
    }
}
