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
use App\Commands\CopyProgramQuestion;

class SaveTestCase extends Command implements SelfHandling
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

        $program = QuestionProgram::findOrFail($this->question->id);
        $this->validate($this->request, [
            'testcase' => 'required|integer|min:1|max:'.$program->test_case,
            'input' => 'required|max:'.config('judge.max_file_size'),
            'output' => 'required|max:'.config('judge.max_file_size'),
        ]);

        $caseId = $this->request->input('testcase');

        $input = File::firstOrNew([
            'id' => $this->question->id,
            'type' => 'input',
            'case' => $caseId,
        ]);
        $input->content = $this->request->input('input');
        if ($input->exists) {
            File::where('id', $input->id)
                ->where('type', $input->type)
                ->where('case', $input->case)
                ->update(['content' => $input->content]);
        } else {
            $input->save();
        }

        $output = File::firstOrNew([
            'id' => $this->question->id,
            'type' => 'output',
            'case' => $caseId,
        ]);
        $output->content = $this->request->input('output');
        if ($output->exists) {
            File::where('id', $output->id)
                ->where('type', $output->type)
                ->where('case', $output->case)
                ->update(['content' => $output->content]);
        } else {
            $output->save();
        }

        $result = $judge->testcase($program->remote, $caseId, [
            'input' => $input->content,
            'output' => $output->content,
        ]);

        return true;
    }
}
