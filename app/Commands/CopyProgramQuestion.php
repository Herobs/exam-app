<?php

namespace App\Commands;

use DB;
use Judge\Judge;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Question;
use App\Models\QuestionProgram;
use App\Models\ProgramLimit as Limit;
use App\Models\ProgramFile as File;

class CopyProgramQuestion extends Command implements SelfHandling
{
    protected $question;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Execute the command.
     */
    public function handle(Judge $judge)
    {
        // program question
        DB::insert('INSERT INTO question_program SELECT ?, remote, title, output_limit, special_judge, test_case FROM question_program WHERE id = ?',
            [$this->question->id, $this->question->ref]);
        // program limits
        DB::insert('INSERT INTO program_limits SELECT ?, type, language, value FROM program_limits WHERE id = ?',
            [$this->question->id, $this->question->ref]);
        // program files
        DB::insert('INSERT INTO program_files SELECT ?, `case`, type, content FROM program_files WHERE id = ?',
            [$this->question->id, $this->question->ref]);
        $this->question->ref = $this->question->id;
        $this->question->save();

        $program = QuestionProgram::findOrFail($this->question->id);
        $result = $judge->copyProblem($program->remote);
        $program->remote = $result->problemId;
        $program->save();

        return true;
    }
}
