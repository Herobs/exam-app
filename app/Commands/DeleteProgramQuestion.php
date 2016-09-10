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

class DeleteProgramQuestion extends Command implements SelfHandling
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
        // delete from judge server
        if ($this->question->id === $this->question->ref) {
            $program = QuestionProgram::find($this->question->id);
            if (!is_null($program) && $program->remote > 0) {
                $result = $judge->removeProblem($program->remote);
            }
        }

        // delete from database
        DB::delete('DELETE questions, question_program, program_limits, program_files' .
            ' FROM questions' .
            ' LEFT JOIN question_program ON questions.id = question_program.id' .
            ' LEFT JOIN program_limits ON questions.id = program_limits.id' .
            ' LEFT JOIN program_files ON questions.id = program_files.id' .
            ' WHERE questions.id = ?', [$this->question->id]);

        return true;
    }
}
