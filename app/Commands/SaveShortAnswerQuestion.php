<?php

namespace App\Commands;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Question;

class SaveShortAnswerQuestion extends Command implements SelfHandling
{
    use ValidatesRequests;
    // question type
    protected $type = 'short-answer';
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
    public function handle()
    {
        $this->validate($this->request, [
            'description' => 'required|max:65535',
            'score' => 'required|integer|max:255',
        ]);

        if ($this->request->has('id')) {
            $question = Question::findOrFail($this->request->input('id'));
            if ($question->user !== $this->user) {
                return false;
            }
            if ($question->id !== $question->ref) {
                $question->ref = $question->id;
            }
        } else {
            if (!hasRight($this->rights, config('rights.RIGHT_QUESTION'))) {
                return false;
            }
            $question = new Question;
            $question->user = $this->user;
            $question->exam = $this->exam;
            $question->type = $this->type;
            $question->save();
            // set ref to id when create new question
            $question->ref = $question->id;
        }
        $question->description = $this->request->input('description');
        $question->score = $this->request->input('score');
        $question->save();

        return true;
    }
}
