<?php

namespace App\Commands;

use Judge\Judge;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramFile as File;

class DeleteTestCase extends Command implements SelfHandling
{
    use ValidatesRequests;
    // http request
    protected $request;
    // question object
    protected $question;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Request $request, Question $question)
    {
        $this->request = $request;
        $this->question = $question;
    }

    /**
     * Execute the command.
     */
    public function handle(Judge $judge)
    {
        if ($this->question->id !== $this->question->ref) {
            dispatch(new CopyProgramQuestion($this->question));
        }

        $caseId = intval($this->request->route('testcase'));
        File::where('id', $this->question->id)
            ->where('case', $caseId)
            ->delete();

        $program = QuestionProgram::findOrFail($this->question->id);

        $result = $judge->RemoveTestCase($program->remote, $caseId);

        return true;
    }
}
