<?php

namespace App\Commands;

use DB;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Models\Question;
use App\Models\StandardMultiChoice as Standard;
use App\Models\QuestionMultiChoice;

class SaveMultiChoiceQuestion extends Command implements SelfHandling
{
    use ValidatesRequests;
    // question type
    protected $type = 'multi-choice';
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
            'orders' => 'required|integer|max:26',
            'answers.*' => 'required|accepted',
            'options.*' => 'required|max:255',
            'score' => 'required|integer|max:255',
        ], ['orders.max' => '最多只能设置 16 个选项。']);

        if ($this->request->has('id')) {
            $question = Question::findOrFail($this->request->input('id'));
            if ($question->user !== $this->user) {
                return false;
            }
            if ($question->id !== $question->ref) {
                // insert answer
                DB::insert('INSERT INTO standard_multi_choice (id, answer) SELECT ?, answer FROM standard_multi_choice WHERE id = ?',
                    [$question->id, $question->ref]);
                $question->ref = $question->id;
            }
            $standard = Standard::findOrFail($question->id);
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

            $standard = new Standard;
        }
        $question->description = $this->request->input('description');
        $question->score = $this->request->input('score');
        $question->save();

        $answer = 0;
        $orders = intval($this->request->input('orders'));
        for ($i = 0; $i < $orders; $i++) {
            if ($this->request->has('options.'.$i)) {
                $option = QuestionMultiChoice::firstOrNew([
                    'id' => $question->id,
                    'order' => $i,
                ]);
                $option->option = $this->request->input('options.'.$i);
                if ($option->exists) {
                    QuestionMultiChoice::where('id', $option->id)
                        ->where('order', $option->order)
                        ->update(['option' => $option->option]);
                } else {
                    $option->save();
                }
            }
            if ($this->request->has('answers.'.$i)) {
                $answer |= 1 << $i;
            }
        }

        $standard->id = $question->id;
        $standard->answer = $answer;
        $standard->save();

        return true;
    }
}
